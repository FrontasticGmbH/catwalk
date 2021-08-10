<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;

class SiteBuilderPageService
{
    const STORAGE_FILE = 'frontastic_nextjs_pathmap.php';

    private string $cacheDir;

    private array $mapping;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @param Route[] $routes
     */
    public function storeSiteBuilderPagePathsFromRoutes(array $routes): void
    {
        file_put_contents(
            $this->getStorageFile(),
            "<?php\n\nreturn " . var_export(self::routesToPathMap($routes), true) . ";\n"
        );
    }

    public function matchSiteBuilderPage(string $path, string $locale): ?string
    {
        $pathMap = $this->getMapping()['pathToNodeId'];

        if (isset($pathMap[$path]) && isset($pathMap[$path][$locale])) {
            return $pathMap[$path][$locale];
        }
        return null;
    }

    private function getMapping(): array
    {
        if (isset($this->mapping)) {
            return $this->mapping;
        }

        if (!file_exists($this->getStorageFile())) {
            return [
                'pathToNodeId' => [],
                'nodeIdToPath' => [],
            ];
        }

        $this->mapping = require $this->getStorageFile();
        return $this->mapping;
    }

    /**
     * @param Route[] $routes
     * @return array
     */
    private static function routesToPathMap(array $routes): array
    {
        $pathToNodeIdMap = [];
        $nodeIdToPathMap = [];

        foreach ($routes as $route) {
            if (!isset($pathToNodeIdMap[$route->route])) {
                $pathToNodeIdMap[$route->route] = [];
            }
            $pathToNodeIdMap[$route->route][$route->locale] = $route->nodeId;

            if (!isset($nodeIdToPathMap[$route->nodeId])) {
                $nodeIdToPathMap[$route->nodeId] = [];
            }
            $nodeIdToPathMap[$route->nodeId][$route->locale] = $route->route;
        }
        return ['pathToNodeId' => $pathToNodeIdMap, 'nodeIdToPath' => $nodeIdToPathMap];
    }

    private function getStorageFile(): string
    {
        return sprintf('%s/%s', $this->cacheDir, self::STORAGE_FILE);
    }
}
