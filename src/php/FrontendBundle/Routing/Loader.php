<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\FrontendBundle\Controller\NodeController;
use Frontastic\Catwalk\FrontendBundle\Controller\RedirectController;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectCacheService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Loader extends BaseLoader
{
    private $loaded = false;

    /**
     * @var RouteService
     */
    private $routeService;

    /**
     * @var RedirectCacheService
     */
    private $redirectCacheService;

    public function __construct(RouteService $routeService, RedirectCacheService $redirectCacheService)
    {
        $this->routeService = $routeService;
        $this->redirectCacheService = $redirectCacheService;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "frontastic" loader twice');
        }

        $routes = new RouteCollection();

        $this->addRoutesToRouteCollection($routes);
        $this->addRedirectsToRouteCollection($routes);

        $this->loaded = true;
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'frontastic' === $type;
    }

    protected function addRoutesToRouteCollection(RouteCollection $routes): void
    {
        foreach ($this->routeService->getRoutes() as $route) {
            $routes->add(
                'node_' . $route->nodeId,
                new Route(
                    $route->route,
                    array(
                        '_controller' => sprintf('%s::viewAction', NodeController::class),
                        'nodeId' => $route->nodeId,
                    )
                )
            );
        }
    }

    protected function addRedirectsToRouteCollection(RouteCollection $routes): void
    {
        foreach ($this->redirectCacheService->getRedirects() as $redirect) {
            $routes->add(
                'redirect_' . $redirect->redirectId,
                new Route(
                    $redirect->path,
                    array(
                        '_controller' => sprintf('%s::redirectAction', RedirectController::class),
                        'redirectId' => $redirect->redirectId,
                    )
                )
            );
        }
    }
}
