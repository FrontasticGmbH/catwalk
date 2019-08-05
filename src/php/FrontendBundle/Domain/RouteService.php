<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;

class RouteService
{
    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * @var string
     */
    private $cacheDirectory;

    public function __construct(CustomerService $customerService, string $cacheDirectory)
    {
        $this->customerService = $customerService;
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\Route[]
     */
    public function getRoutes(): array
    {
        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        return [];
    }

    public function storeRoutes(array $routes): void
    {
        file_put_contents(
            $this->getCacheFile(),
            '<?php return ' . var_export($routes, true) . ';'
        );

        // @HACK There seems not to be a sane way to rebuild just the route
        // cache so that we force rebuild by removing the old cache files
        foreach (glob($this->cacheDirectory . '/*Url*') as $routerCacheFile) {
            unlink($routerCacheFile);
        }
    }

    protected function getCacheFile(): string
    {
        return $this->cacheDirectory . '/frontastic_frontent_routes.php';
    }

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\Node[] $nodes
     */
    public function rebuildRoutes(array $nodes): void
    {
        $this->storeRoutes(array_values($this->generateRoutes($nodes)));
    }

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\Node[] $nodes
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\Route[]
     */
    public function generateRoutes(array $nodes): array
    {
        $routes = [];

        usort(
            $nodes,
            function (Node $a, Node $b) {
                return (strlen($a->path) - strlen($b->path));
            }
        );

        foreach ($nodes as $node) {
            if (empty($node->configuration['path'])) {
                // Ignore routes where no path is defined. Those are probably
                // not supposed to be rendered.
                continue;
            }

            $parents = array_filter(explode('/', $node->path));

            if (!count($parents)) {
                $routes = array_merge($routes, $this->generateRoutesForNode($node));
                continue;
            }

            $parent = end($parents);

            $parentRoutes = $this->findParentRoutes($parent, $routes);

            if (count($parentRoutes) === 0) {
                // Just ignore routes without parents – this just might happen
                // from time to time because of temporary inconsistencies
                continue;
            }

            $routes = array_merge($routes, $this->generateRoutesForNode($node, $parentRoutes));
        }
        return $routes;
    }

    private function findParentRoutes($parentNodeId, array $routes): array
    {
        return array_filter(
            $routes,
            function (Route $route) use ($parentNodeId) {
                return $route->nodeId === $parentNodeId;
            }
        );
    }

    private function generateRoutesForNode(Node $node, array $parentRoutes = []): array
    {
        $project = reset($this->customerService->getCustomer()->projects);

        $routes = [];
        foreach ($project->languages as $locale) {
            $relativeRoute = '/' . trim(
                    $this->relativeRouteFor($node, $locale),
                    '/'
                );

            $parentPath = $this->determineParentPath($parentRoutes, $locale);

            $routes[] = new Route([
                'nodeId' => $node->nodeId,
                'route' => rtrim($parentPath, '/') . $relativeRoute,
                'locale' => $locale,
            ]);
        }

        return $routes;
    }

    private function relativeRouteFor(Node $node, string $locale): string
    {
        if (isset($node->configuration['pathTranslations'][$locale])) {
            return $node->configuration['pathTranslations'][$locale];
        }

        return $node->configuration['path'] ?? '';
    }

    /**
     * @param Route[] $parentRoutes
     * @param string $locale
     * @return string
     */
    private function determineParentPath(array $parentRoutes, string $locale): string
    {
        foreach ($parentRoutes as $route) {
            if ($route->locale === $locale) {
                return $route->route;
            }
        }
        return '';
    }
}
