<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Routing\Route;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\AccountApiBundle\Domain\Account;

class ContextService
{
    private $router;

    private $requestStack;

    private $customerService;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var ContextDecorator[]
     */
    private $decorators = [];

    public function __construct(
        Router $router,
        RequestStack $requestStack,
        CustomerService $customerService,
        TokenStorage $tokenStorage,
        iterable $decorators
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->customerService = $customerService;
        $this->tokenStorage = $tokenStorage;

        foreach ($decorators as $decorator) {
            $this->addDecorator($decorator);
        }
    }

    public function addDecorator(ContextDecorator $decorator): void
    {
        $this->decorators[] = $decorator;
    }

    public function createContextFromRequest(Request $request = null): Context
    {
        $request = $request ?: $this->requestStack->getCurrentRequest();

        return $this->getContext(
            $request ? $request->get('locale', 'en_GB') : 'en_GB',
            $request ? $this->getSession($request) : new Session()
        );
    }

    public function getSession(Request $request): Session
    {
        /* HACK: Is this request is stateless, so let the ContextService know that we do not need a session. */
        $attributes = $request->attributes;
        if ($attributes->get(Session::STATELESS) && true === $attributes->get(Session::STATELESS)) {
            return new Session([
                'loggedIn' => true,
                'account' => new Account(['accountId' => 'system.stateless.call']),
            ]);
        }

        try {
            $token = $this->tokenStorage->getToken();

            if ($token === null) {
                return $this->getAnonymousSession($request);
            }

            $account = $token->getUser();
            if (!($account instanceof Account)) {
                return $this->getAnonymousSession($request);
            }

            return new Session([
                'loggedIn' => true,
                'account' => $account,
            ]);
        } catch (UnauthenticatedUserException $e) {
            $session = $this->getAnonymousSession($request);
            $session->message = $e->getMessage() ? ('Unauthenticated: ' . $e->getMessage()) : null;
            return $session;
        }
    }

    private function getAnonymousSession(Request $request): Session
    {
        $session = $request->getSession();

        if ($session->has('anonymousId')) {
            return new Session([
                'loggedIn' => false,
                'account' => new Account(['accountId' => $session->get('anonymousId')]),
            ]);
        }

        $anonymousId = md5(json_encode([
            'languages' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '',
            'agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            // @TODO: If we replace this by more user-specifica (time zone,
            // resolution, …) this might enable us to recognize users and
            // associate them with the same anonymousId (See: evercookie /
            // Panopticlick)
            'time' => microtime(),
        ]));
        $session->set('anonymousId', $anonymousId);

        return new Session([
            'loggedIn' => false,
            'account' => new Account(['accountId' => $anonymousId]),
        ]);
    }

    public function getContext(string $locale = 'en_GB', Session $session = null): Context
    {
        $customer = $this->customerService->getCustomer();
        $project = reset($customer->projects);

        if (!in_array($locale, $project->languages)) {
            $locale = $project->defaultLanguage;
        }

        $context = new Context([
            'environment' => \Frontastic\Catwalk\AppKernel::getEnvironmentFromConfiguration(),
            'customer' => $customer,
            'project' => $project,
            'locale' => $locale,
            // @TODO: Build currency from locale
            'currency' => 'EUR',
            'session' => $session ?: new Session(),
            'routes' => array_map(
                function (Route $route): object {
                    return (object) [
                        'path' => $route->getPath(),
                        'requirements' => (object) $route->getRequirements(),
                    ];
                },
                iterator_to_array($this->router->getRouteCollection())
            ),
        ]);

        foreach ($this->decorators as $decorator) {
            $context = $decorator->decorate($context);
        }

        return $context;
    }
}
