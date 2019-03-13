<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\FrontendBundle\Controller\NodeController;
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
     * @var string
     */
    private $nodeControllerClass;

    public function __construct(RouteService $routeService, string $nodeControllerClass = NodeController::class)
    {
        $this->routeService = $routeService;
        $this->nodeControllerClass = $nodeControllerClass;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "frontastic" loader twice');
        }

        $routes = new RouteCollection();

        $this->addRoutesToRouteCollection($routes);

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
                        '_controller' => sprintf('%s::viewAction', $this->nodeControllerClass),
                        'nodeId' => $route->nodeId,
                    )
                )
            );
        }
    }
}
