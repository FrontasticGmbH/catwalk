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

    public function __construct(Router $router, RequestStack $requestStack, CustomerService $customerService, TokenStorage $tokenStorage)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->customerService = $customerService;
        $this->tokenStorage = $tokenStorage;
    }

    public function createContextFromRequest(Request $request = null): Context
    {
        $request = $request ?: $this->requestStack->getCurrentRequest();

        return $this->getContext(
            $request ? $request->get('locale', 'en_GB') : 'en_GB',
            $request ? $this->getSession($request) : new Session()
        );
    }

    /**
     * @param Request $request Deprecated, is not required
     * @return Session
     */
    public function getSession(Request $request): Session
    {
        try {
            $token = $this->tokenStorage->getToken();

            if ($token === null) {
                return new Session([
                    'loggedIn' => false,
                    'account' => null,
                ]);
            }

            $account = $token->getUser();
            if (!($account instanceof Account)) {
                $account = null;
            }

            return new Session([
                'loggedIn' => (bool) $account,
                'account' => $account,
            ]);
        } catch (UnauthenticatedUserException $e) {
            return new Session([
                'loggedIn' => false,
                'message' => $e->getMessage() ? ('Unauthenticated: ' . $e->getMessage()) : null,
            ]);
        }
    }

    public function getContext(string $locale = 'en_GB', Session $session = null): Context
    {
        $customer = $this->customerService->getCustomer();
        $project = reset($customer->projects);

        if (!in_array($locale, $project->languages)) {
            $locale = $project->defaultLanguage;
        }

        return new Context([
            'environment' => \Frontastic\Catwalk\AppKernel::getEnvironmentFromConfiguration(),
            'customer' => $customer,
            'project' => $project,
            'locale' => $locale,
            // @TODO: Build currency from locale
            'currency' => 'EUR',
            'session' => $session ?: new Session(),
            'routes' => array_map(
                function (Route $route): string {
                    return $route->getPath();
                },
                iterator_to_array($this->router->getRouteCollection())
            ),
        ]);
    }
}
