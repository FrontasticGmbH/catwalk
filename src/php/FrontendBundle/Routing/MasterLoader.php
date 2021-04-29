<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MasterLoader extends BaseLoader
{
    const MASTER_ROUTE_ID = 'Frontastic.Frontend.Master';

    private $loaded = false;

    /**
     * @var Project
     */
    private $project;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Project $project, LoggerInterface $logger)
    {
        $this->project = $project;
        $this->logger = $logger;
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
        $masterRoutesConfig = $this->project->configuration['masterRoutes'] ?? [];

        $catwalkFrontendBundleResourcesConfigDir = __DIR__ . '/../Resources/config';
        $catwalkFrontendBundleResourcesConfigFile = $catwalkFrontendBundleResourcesConfigDir . '/routing_master.xml';
        if (!file_exists($catwalkFrontendBundleResourcesConfigFile)) {
            $this->logger->warning(
                sprintf(
                    'Error fetching the file %s. can not be found',
                    $catwalkFrontendBundleResourcesConfigFile
                )
            );
            return;
        }

        $xmlDoc = new SimpleXMLElement(
            file_get_contents($catwalkFrontendBundleResourcesConfigFile)
        );

        foreach ($masterRoutesConfig as $masterRouteConfig) {
            if (!isset($masterRouteConfig['id']) ||
                !isset($masterRouteConfig['path'])
            ) {
                $this->logger->warning(
                    "Error fetching masterRoute required fields 'id' or 'path' from project configuration"
                );
                continue;
            }

            $masterRouteId = self::MASTER_ROUTE_ID . '.' . $masterRouteConfig['id'];

            $defaultMasterRoute = $this->getDefaultMasterRoute($xmlDoc, $masterRouteId);

            if (!$defaultMasterRoute) {
                $this->logger->warning(
                    sprintf(
                        'Error fetching default master route (id: %s)',
                        $masterRouteId
                    )
                );
                continue;
            }

            $routes->add(
                $masterRouteId,
                new Route(
                    $masterRouteConfig['path'],
                    $this->getDefaults($defaultMasterRoute),
                    $this->getRequirements($defaultMasterRoute, $masterRouteConfig),
                    [],
                    null,
                    [],
                    $this->getMethods($defaultMasterRoute)
                )
            );
        }
    }

    protected function getDefaultMasterRoute(
        SimpleXMLElement $defaultMasterRoutes,
        string $masterRouteId
    ): ?SimpleXMLElement {
        /** @var SimpleXMLElement $defaultMasterRoute */
        foreach ($defaultMasterRoutes->route as $defaultMasterRoute) {
            if ((string)$defaultMasterRoute->attributes()['id'] == $masterRouteId) {
                return $defaultMasterRoute;
            }
        }

        return null;
    }

    protected function getDefaults(SimpleXMLElement $defaultMasterRoute): array
    {
        $defaults = [];
        foreach ($defaultMasterRoute->default as $default) {
            $defaults[(string)$default->attributes()['key']] = (string)$default;
        }

        return $defaults;
    }

    protected function getRequirements(SimpleXMLElement $defaultMasterRoute, array $masterRouteConfig): array
    {
        $requirements = [];
        foreach ($defaultMasterRoute->requirement as $requirement) {
            $requirements[(string)$requirement->attributes()['key']] = (string)$requirement;
        }

        if (isset($masterRouteConfig['allowSlashInUrl'])) {
            if ($masterRouteConfig['allowSlashInUrl']) {
                $requirements['url'] = ".+";
            } else {
                unset($requirements['url']);
            }
        }

        return $requirements;
    }

    protected function getMethods(SimpleXMLElement $defaultMasterRoute)
    {
        return $defaultMasterRoute->attributes()['methods'] ?
            explode(',', $defaultMasterRoute->attributes()['methods']) : [];
    }
}
