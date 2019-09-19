<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Locale;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Yes, context encapsulates quite some stuff
 */
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

    /**
     * @var LocaleResolver
     */
    private $localeResolver;

    /**
     * @HACK This is a hack to cache getting the same context over and over again.
     */
    static private $contextCache = [];

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

        $this->localeResolver = new LocaleResolver();

        foreach ($decorators as $decorator) {
            $this->addDecorator($decorator);
        }
    }

    public function addDecorator(ContextDecorator $decorator): void
    {
        $this->decorators[] = $decorator;
    }

    /**
     * Creates the Context for a given request. Falls back to current request.
     *
     * If no $request is provided the method falls back to the current request from the RequestStack. NOTE: This is
     * not recommended. If possible, please get hold on the Request you want to react on instead of relying on this
     * magic!
     *
     * @param Request|null $request
     * @return Context
     */
    public function createContextFromRequest(Request $request = null): Context
    {
        $request = $request ?: $this->requestStack->getCurrentRequest();

        return $this->getContext(
            $request
                ? $this->localeResolver->determineLocale($request, $this->getProject())
                : $this->getProject()->defaultLanguage,
            $request ? $this->getSession($request) : new Session()
        );
    }

    private function getProject(): Project
    {
        return reset($this->customerService->getCustomer()->projects);
    }

    /**
     * Get the current context using $locale and $session.
     *
     * This method is meant to be used in cases where $locale and $session are known or do not matter. If you need to
     * create a context for a request, please use {@see ContextService::createContextFromRequest()} instead!
     *
     * @param string|null $locale
     * @param Session|null $session
     * @return Context
     */
    public function getContext(string $locale = null, Session $session = null): Context
    {
        $contextCacheHash = $locale . '-' . md5(json_encode($session));
        if (isset(ContextService::$contextCache[$contextCacheHash])) {
            return ContextService::$contextCache[$contextCacheHash];
        }

        $customer = $this->customerService->getCustomer();

        // By definition there is only 1 project available in the customer
        $project = reset($customer->projects);

        if (!in_array($locale, $project->languages)) {
            $locale = $project->defaultLanguage;
        }

        $localeObject = Locale::createFromPosix($locale);

        $context = new Context([
            'environment' => \Frontastic\Catwalk\AppKernel::getEnvironmentFromConfiguration(),
            'customer' => $customer,
            'project' => $project,
            'locale' => $locale,
            'currency' => $localeObject->currency,
            'session' => $session ?: new Session(),
            'routes' => $this->getRoutes($locale),
        ]);

        foreach ($this->decorators as $decorator) {
            $context = $decorator->decorate($context);
        }

        return ContextService::$contextCache[$contextCacheHash] = $context;
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

        if ($session !== null && $session->has('anonymousId')) {
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

        if ($session instanceof SessionInterface) {
            // This was disabled in order to make the maintenance page request listener work.
            $session->set('anonymousId', $anonymousId);
        }

        return new Session([
            'loggedIn' => false,
            'account' => new Account(['accountId' => $anonymousId]),
        ]);
    }

    private function getRoutes(string $locale): array
    {
        $routes = array_map(
            function (Route $route): object {
                return (object)[
                    'path' => $route->getPath(),
                    '_locale' => $route->getDefault('_locale'),
                    '_canonical_route' => $route->getDefault('_canonical_route'),
                    'requirements' => (object)$route->getRequirements(),
                ];
            },
            iterator_to_array($this->router->getRouteCollection())
        );

        $language = null;
        $localeParts = explode('_', $locale);
        if (count($localeParts) === 2) {
            $language = $localeParts[0];
        }

        foreach ($routes as $id => $route) {
            $routeLocale = $route->_locale;
            $canonicalRoute = $route->_canonical_route;

            if ($routeLocale === null || $canonicalRoute === null) {
                continue;
            }

            if ($routeLocale === $locale && !isset($routes[$canonicalRoute])) {
                $routes[$canonicalRoute] = $route;
            }

            $localeRoute = $canonicalRoute . '.' . $locale;
            if ($routeLocale === $language && !isset($routes[$canonicalRoute]) && !isset($routes[$localeRoute])) {
                $routes[$canonicalRoute] = $route;
            }
        }

        return $routes;
    }
}
