<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolverInterface;
use Frontastic\Catwalk\AppKernel as AppKernelAlias;
use Frontastic\Catwalk\FrontendBundle\Session\StatelessSessionHelper;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\Mvc\Exception\UnauthenticatedUserException;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Locale;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) Yes, context encapsulates quite some stuff
 */
class ContextService
{
    private $router;

    private $requestStack;

    private $customerService;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ContextDecorator[]
     */
    private $decorators = [];

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    /**
     * @HACK This is a hack to cache getting the same context over and over again.
     */
    private $contextCache = [];

    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(
        Router $router,
        RequestStack $requestStack,
        CustomerService $customerService,
        ProjectService $projectService,
        TokenStorageInterface $tokenStorage,
        LocaleResolverInterface $localeResolver,
        iterable $decorators
    ) {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->customerService = $customerService;
        $this->projectService = $projectService;
        $this->tokenStorage = $tokenStorage;
        $this->localeResolver = $localeResolver;

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
     * @param ?Request $request
     * @return Context
     */
    public function createContextFromRequest(Request $request = null): Context
    {
        $request = $request ?: $this->requestStack->getCurrentRequest();
        $project = $this->projectService->getProject();

        return $this->getContext(
            $request
                ? $this->localeResolver->determineLocale($request, $project)
                : $project->defaultLanguage,
            $request ? $this->getSession($request) : new Session(),
            $request ? $request->getHost() : null
        );
    }

    /**
     * Get the current context using $locale and $session.
     *
     * This method is meant to be used in cases where $locale and $session are known or do not matter. If you need to
     * create a context for a request, please use {@see ContextService::createContextFromRequest()} instead!
     *
     * @param ?string $locale
     * @param ?Session $session
     * @param ?string $host
     * @return Context
     */
    public function getContext(string $locale = null, Session $session = null, string $host = null): Context
    {
        $contextCacheHash = $locale . '-' . md5(Json::encode($session));
        if (isset($this->contextCache[$contextCacheHash])) {
            return $this->contextCache[$contextCacheHash];
        }

        $customer = $this->customerService->getCustomer();
        $project = $this->projectService->getProject();

        if ($locale === null) {
            $locale = $project->defaultLanguage;
        }

        $localeObject = Locale::createFromPosix($locale);

        if ($host === null) {
            $host = parse_url($project->publicUrl ?? $project->previewUrl ?? '', PHP_URL_HOST);
        }

        $context = new Context([
            'environment' => AppKernelAlias::getEnvironmentFromConfiguration(),
            'customer' => $customer,
            'project' => $project,
            'locale' => $locale,
            'currency' => $localeObject->currency,
            'session' => $session ?: new Session(),
            'routes' => $this->getRoutes($locale),
            'host' => $host,
        ]);

        foreach ($this->decorators as $decorator) {
            $context = $decorator->decorate($context);
        }

        return $this->contextCache[$contextCacheHash] = $context;
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
        $session = StatelessSessionHelper::hasSession($request) ? $request->getSession() : null;

        if ($session !== null && $session->has('anonymousId')) {
            return new Session([
                'loggedIn' => false,
                'account' => new Account(['accountId' => $session->get('anonymousId')]),
            ]);
        }

        $anonymousId = md5(Json::encode([
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
                    '_frontastic_canonical_route' => $route->getDefault('_frontastic_canonical_route'),
                    'requirements' => (object)$route->getRequirements(),
                ];
            },
            iterator_to_array($this->router->getRouteCollection())
        );

        $language = $this->getLanguageFromLocaleWithTerritory($locale);

        foreach ($routes as $id => $route) {
            $routeLocale = $route->_locale;
            $canonicalRoute = $route->_frontastic_canonical_route;

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

    private function getLanguageFromLocaleWithTerritory(string $locale): ?string
    {
        $localeParts = explode('_', $locale);
        if (count($localeParts) === 2) {
            return $localeParts[0];
        }

        return null;
    }
}
