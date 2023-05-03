<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\FrontendBundle\Controller\NodeController;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\NextJsBundle\Domain\FrontasticNextJsRouteService;
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

        // We don't use the Frontastic React way of routing in coFE, so we just return an empty RouteCollection
        if ($this->routeService instanceof FrontasticNextJsRouteService) {
            return $routes;
        }

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
                'node_' . $route->nodeId . '.' . $route->locale,
                new Route(
                    $route->route,
                    array(
                        '_controller' => sprintf('%s::viewAction', $this->nodeControllerClass),
                        '_locale' => $route->locale,
                        '_frontastic_canonical_route' => 'node_' . $route->nodeId,
                        'nodeId' => $route->nodeId,
                    ),
                    [],
                    ['utf8' => true]
                )
            );
        }
    }
}
