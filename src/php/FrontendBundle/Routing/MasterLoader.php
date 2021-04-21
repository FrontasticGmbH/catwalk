<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MasterLoader extends BaseLoader
{
    const MASTER_ROUTE_ID = 'Frontastic.Frontend.Master';

    private $loaded = false;

    /**
     * @var ProjectService
     */
    private $projectService;

    public function __construct(ProjectService $projectService) {
        $this->projectService = $projectService;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "master" loader twice');
        }

        $routes = new RouteCollection();

        $this->addMasterRoutesToRouteCollection($routes);

        $this->loaded = true;
        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'master' === $type;
    }

    protected function addMasterRoutesToRouteCollection(RouteCollection $routes): void
    {
        $masterRoutes = $this->projectService->getProject()->configuration['masterRoutes'];

        foreach ($masterRoutes as $masterRoute) {
            if (!isset($masterRoute['id']) ||
                !isset($masterRoute['path']) ||
                !isset($masterRoute['defaults'])
            ) {
                continue;
            }

            $routes->add(
                self::MASTER_ROUTE_ID . '.' . $masterRoute['id'],
                new Route(
                    $masterRoute['path'],
                    $masterRoute['defaults'] ?? [],
                    $masterRoute['requirements'] ?? [],
                    $masterRoute['options'] ?? [],
                    $masterRoute['host'] ?? null,
                    $masterRoute['schemes'] ?? [],
                    $masterRoute['methods'] ?? [],
                    $masterRoute['condition'] ?? null
                )
            );
        }
    }
}
