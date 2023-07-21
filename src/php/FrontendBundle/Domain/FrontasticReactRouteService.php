<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\FrontendBundle\Gateway\FrontendRoutesGateway;
use Symfony\Component\Filesystem\Filesystem;

class FrontasticReactRouteService implements RouteService
{
    // ID used to cache the Frontend Routes in DB
    const CACHE_ID = 1;

    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * @var string
     */
    private $cacheDirectory;

    /**
     * @var FrontendRoutesGateway
     */
    private $frontendRoutesGateway;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $routeSuffix = '';

    public function __construct(
        CustomerService $customerService,
        string $cacheDirectory,
        FrontendRoutesGateway $frontendRoutesGateway,
        Filesystem $filesystem
    ) {
        $this->customerService = $customerService;
        $this->cacheDirectory = $cacheDirectory;
        $this->frontendRoutesGateway = $frontendRoutesGateway;
        $this->filesystem = $filesystem;

        if (getenv('append_slash_to_node_routes') === '1') {
            $this->routeSuffix = '/';
        }
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        if ($this->databaseRouting()) {
            try {
                return $this->frontendRoutesGateway->getById(self::CACHE_ID)->frontendRoutes;
            } catch (\Throwable $e) {
                return [];
            }
        }

        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        return [];
    }

    public function storeRoutes(array $routes): void
    {
        if ($this->databaseRouting()) {
            try {
                $frontendRoutes = $this->frontendRoutesGateway->getById(self::CACHE_ID);
            } catch (\Throwable $e) {
                // Frontend routes does not exist
                $frontendRoutes = new FrontendRoutes();
                $frontendRoutes->frontendRoutesId = self::CACHE_ID;
            }

            $frontendRoutes->frontendRoutes = $routes;

            $this->frontendRoutesGateway->store($frontendRoutes);
        } else {
            $this->filesystem->dumpFile(
                $this->getCacheFile(),
                '<?php return ' . var_export($routes, true) . ';'
            );
        }

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
     * @param Node[] $nodes
     */
    public function rebuildRoutes(array $nodes): void
    {
        $this->storeRoutes(array_values($this->generateRoutes($nodes)));
    }

    /**
     * @param Node[] $nodes
     * @return Route[]
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

        $localesByUrl = [];

        $routes = [];
        foreach ($project->languages as $locale) {
            $relativeRoute =
                '/' .
                trim(
                    $this->relativeRouteFor($node, $locale),
                    '/'
                );

            $parentPath = $this->determineParentPath($parentRoutes, $locale);

            $generatedUrl = rtrim($parentPath, '/') . $relativeRoute . $this->routeSuffix;

            if (!array_key_exists($generatedUrl, $localesByUrl)) {
                $localesByUrl[$generatedUrl] = [];
            }
            $localesByUrl[$generatedUrl][] = $locale;

            $routes[] = new Route([
                'nodeId' => $node->nodeId,
                'route' => $generatedUrl,
                'locale' => $locale,
            ]);
        }

        foreach ($routes as $route) {
            $route->matchingLocales = $localesByUrl[$route->route] ?? [];
        }

        return $routes;
    }

    private function relativeRouteFor(Node $node, string $locale): string
    {
        if (isset($node->configuration['pathTranslations'][$locale])) {
            return $node->configuration['pathTranslations'][$locale];
        }

        $language = explode('_', $locale)[0] ?? 'doesNotExist';
        if (isset($node->configuration['pathTranslations'][$language])) {
            return $node->configuration['pathTranslations'][$language];
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

    private function databaseRouting(): bool
    {
        return getenv('database_routing') === '1';
    }
}
