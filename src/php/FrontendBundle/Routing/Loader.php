<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;

class Loader extends BaseLoader
{
    private $loaded = false;

    /**
     * @var RouteService
     */
    private $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "frontastic" loader twice');
        }

        $routes = new RouteCollection();
        foreach ($this->routeService->getRoutes() as $route) {
            $routes->add(
                'node_' . $route->nodeId,
                new Route(
                    $route->route,
                    array(
                        '_controller' => 'FrontasticCatwalkFrontendBundle:Node:view',
                        'nodeId' => $route->nodeId,
                    )
                )
            );
        }

        $this->loaded = true;
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'frontastic' === $type;
    }
}
