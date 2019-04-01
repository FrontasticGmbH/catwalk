<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

class RedirectCacheService
{
    /**
     * @var string
     */
    private $cacheDirectory;

    public function __construct(string $cacheDirectory)
    {
        $this->cacheDirectory = $cacheDirectory;
    }

    /**
     * @return Redirect[]
     */
    public function getRedirects(): array
    {
        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile)) {
            return include $cacheFile;
        }

        return [];
    }

    /**
     * @param Redirect[] $redirects
     */
    public function storeRedirects(array $redirects): void
    {
        file_put_contents(
            $this->getCacheFile(),
            '<?php return ' . var_export($redirects, true) . ';'
        );

        // @HACK There seems not to be a sane way to rebuild just the route
        // cache so that we force rebuild by removing the old cache files
        foreach (glob($this->cacheDirectory . '/*Url*') as $routerCacheFile) {
            unlink($routerCacheFile);
        }
    }

    protected function getCacheFile(): string
    {
        return $this->cacheDirectory . '/frontastic_frontent_redirects.php';
    }
}
