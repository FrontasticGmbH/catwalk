<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\FrontendBundle\Domain\Sitemap;
use Frontastic\Catwalk\FrontendBundle\Domain\SitemapService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CountInLoopExpression)
 */
class GenerateSitemapsCommand extends ContainerAwareCommand
{
    const MAX_ENTRIES = 10000;

    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var int
     */
    private $maxEntries;

    /**
     * @var bool
     */
    private $singleSitemap;

    /**
     * @var string[]
     */
    private $excludes = [];

    /**
     * @var string
     */
    private $publicUrl;

    private bool $useDatabase = false;

    protected function configure(): void
    {
        $this
            ->setName('frontastic:sitemap:generate')
            ->setDescription('Generates static sitemap files')
            ->addArgument(
                'output-directory',
                InputArgument::REQUIRED,
                'Target directory for the sitemap files'
            )
            ->addOption(
                'all',
                null,
                InputOption::VALUE_NONE,
                'Generate all available sitemap types'
            )
            ->addOption(
                'with-nodes',
                null,
                InputOption::VALUE_NONE,
                'Generate sitemap for nodes'
            )
            ->addOption(
                'with-nodes-subpages',
                null,
                InputOption::VALUE_NONE,
                'Generate sitemap for nodes only accessible by pager'
            )
            ->addOption(
                'with-categories',
                null,
                InputOption::VALUE_NONE,
                'Generate sitemap for categories'
            )
            ->addOption(
                'with-products',
                null,
                InputOption::VALUE_NONE,
                'Generate sitemap for products'
            )
            ->addOption(
                'with-extensions',
                null,
                InputOption::VALUE_NONE,
                'Generate sitemap with custom extensions'
            )
            ->addOption(
                'max-entries',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum number of url entries in a sitemap file',
                self::MAX_ENTRIES
            )
            ->addOption(
                'exclude',
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Pattern to exclude all urls that match'
            )->addOption(
                'locale',
                null,
                InputOption::VALUE_REQUIRED,
                'Explicitly select a locale for the generated URLs'
            )->addOption(
                'base-url',
                null,
                InputOption::VALUE_REQUIRED,
                'Explicitly set the base URL for the sitemap URLs (defaults to publicUrl from project.yml)'
            )->addOption(
                'single-sitemap',
                null,
                InputOption::VALUE_NONE,
                'Generate all sitemaps in a single sitemap.xml file'
            );
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity) Batch code
     * @SuppressWarnings(PHPMD.NPathComplexity) Batch code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->useDatabase = (getenv('database_sitemaps') !== false);

        if ($this->useDatabase === false) {
            $output->writeln(
                '<comment>We recommend switching to database sitemaps to prevent ' .
                'serving outdated information on scaling.</comment>'
            );
            $output->writeln(
                '<comment>Find more information about this in ' .
                'https://docs.frontastic.cloud/changelog/changes-to-sitemaps</comment>'
            );
        }

        $this->maxEntries = $input->getOption('max-entries');
        $this->singleSitemap = $input->getOption('single-sitemap');
        $this->workingDir = uniqid(sprintf('%s/sitemap_', sys_get_temp_dir()));
        $this->excludes = $input->getOption('exclude');
        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($this->workingDir);

        /** @var ContextService $contextService */
        $contextService = $this->getContainer()->get(ContextService::class);
        $context = $contextService->getContext($input->getOption('locale'));

        $this->publicUrl = $this->determinePublicUrl($context, $input->getOption('base-url'));

        $sitemaps = [];
        $entries = [];

        if ($input->getOption('all') || $input->getOption('with-nodes')) {
            $result = $this->generateNodeSitemap($context, $input, $output);
            if ($this->singleSitemap) {
                $entries = array_merge($entries, $result);
            } else {
                $sitemaps = array_merge($sitemaps, $result);
            }
        }

        if ($input->getOption('all') || $input->getOption('with-categories')) {
            $result = $this->generateCategorySitemap($context, $input, $output);
            if ($this->singleSitemap) {
                $entries = array_merge($entries, $result);
            } else {
                $sitemaps = array_merge($sitemaps, $result);
            }
        }

        if ($input->getOption('all') || $input->getOption('with-products')) {
            $result = $this->generateProductSitemap($context, $output);
            if ($this->singleSitemap) {
                $entries = array_merge($entries, $result);
            } else {
                $sitemaps = array_merge($sitemaps, $result);
            }
        }

        if ($input->getOption('all') || $input->getOption('with-extensions')) {
            $result = $this->generateSitemapExtensions($context, $output);
            if ($this->singleSitemap) {
                $entries = array_merge($entries, $result);
            } else {
                $sitemaps = array_merge($sitemaps, $result);
            }
        }

        $outputDir = $input->getArgument('output-directory');
        $basePath = $this->cleanBasePath($outputDir);

        $output->writeln('Generating sitemap index…');
        $filePath = 'sitemap_index.xml';
        if ($this->singleSitemap) {
            $this->renderSitemap($this->publicUrl, $this->filterEntries($entries), $filePath);
        } else {
            $sitemaps = array_map(function ($sitemap) use ($basePath) {
                return $basePath . $sitemap;
            }, $sitemaps);

            $this->renderIndex($this->publicUrl, $sitemaps, $filePath);
        }

        if (!$this->useDatabase) {
            $this->storeInFilesystem($outputDir);
        } else {
            $this->storeInDatabase($outputDir);
        }

        return 0;
    }

    private function determinePublicUrl(Context $context, $overrideUrl = null): string
    {
        if ($overrideUrl !== null) {
            return $overrideUrl;
        }

        if (isset($context->project->publicUrl)) {
            return $context->project->publicUrl;
        }

        // Guess!
        $previewUrlParts = parse_url($context->project->previewUrl);
        return sprintf('%s://%s', $previewUrlParts['scheme'], $previewUrlParts['host']);
    }

    private function generateSitemapExtensions(Context $context, OutputInterface $output): array
    {
        /** @var SitemapService $sitemapService */
        $sitemapService = $this->getContainer()->get(SitemapService::class);

        $sitemaps = [];
        $allExtensionEntries = [];
        foreach ($sitemapService->getExtensions() as $extension) {
            $output->writeln('Generating sitemaps for extension ' . get_class($extension) . ' …');

            try {
                $entries = [];
                $urls = $extension->getEntries();

                if (is_array(reset($urls))) {
                    foreach ($urls as $entry) {
                        $entries[] = [
                            'uri' => $entry['uri'],
                            'changed' => $entry['changed'],
                        ];
                    }
                } else {
                    foreach ($urls as $url) {
                        $entries[] = [
                            'uri' => $url,
                            'changed' => time(),
                        ];
                    }
                }

                $output->writeln("Generating {$extension->getName()} sitemaps…");

                if ($this->singleSitemap) {
                    $allExtensionEntries = array_merge($allExtensionEntries, $entries);
                } else {
                    $sitemaps = array_merge(
                        $sitemaps,
                        $this->renderSitemaps($context, $entries, $extension->getName())
                    );
                }
            } catch (\Throwable $error) {
                $output->writeln(
                    '<error>Sitemap extension ' . get_class($extension) . ' resulted in an error:</error>'
                );
                $output->writeln('<error>' . $error->getMessage() . '</error>');

                $entries = [];
                continue;
            }

            $output->writeln('<info>Sitemap extension ' . get_class($extension) . ' finished successfully.</info>');
        }

        return $this->singleSitemap ? $allExtensionEntries : $sitemaps;
    }

    private function generateNodeSitemap(Context $context, InputInterface $input, OutputInterface $output): array
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->getContainer()->get(NodeService::class);
        /** @var RouteService $routeService */
        $routeService = $this->getContainer()->get(RouteService::class);
        /** @var PageService $pageService */
        $pageService = $this->getContainer()->get(PageService::class);

        $output->writeln('Generating node sitemaps…');

        $nodes = $nodeService->getNodes();
        $routes = $routeService->generateRoutes($nodes);

        $entries = [];
        foreach ($nodes as $node) {
            try {
                // Only render urls for nodes with pages
                $pageService->fetchForNode($node, $context);
            } catch (\Exception $e) {
                continue;
            }

            $nodeRoutes = $this->getRoutesForNode($routes, $node);

            if (empty($nodeRoutes)) {
                // there is no route for this node, maybe some parent nodes in the tree of this node has been deleted.
                continue;
            }

            $route = $this->getRouteForCurrentContext($context, $nodeRoutes);

            $entries[] = [
                'uri' => $route->route,
                'changed' => strtotime($node->metaData['changed']),
            ];

            if (!$input->getOption('with-nodes-subpages')) {
                continue;
            }
            $entries = array_merge(
                $entries,
                $this->generatePagerEntries(
                    $node,
                    $context,
                    $route->route
                )
            );
        }

        return $this->singleSitemap ? $entries : $this->renderSitemaps($context, $entries, 'nodes');
    }

    private function generateCategorySitemap(Context $context, InputInterface $input, OutputInterface $output): array
    {
        $limit = min(500, $this->maxEntries);

        /** @var ProductApi $productApi */
        $productApi = $this->getContainer()->get(ProductApi::class);
        /** @var UrlGeneratorInterface $urlGenerator */
        $urlGenerator = $this->getContainer()->get('router');
        /** @var MasterService $pageMatcherService */
        $masterService = $this->getContainer()->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->getContainer()->get(NodeService::class);

        $output->writeln('Generating category sitemaps…');

        $query = new CategoryQuery([
            'locale' => $context->locale,
            'limit' => $limit
        ]);

        $entries = [];

        do {
            /** @var \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result $result */
            $result = $productApi->queryCategories($query);

            foreach ($result as $category) {
                $uri = $urlGenerator->generate(
                    'Frontastic.Frontend.Master.Category.view',
                    [
                        'id' => $category->categoryId,
                        'slug' => strtolower(rawurlencode($category->name)),
                    ]
                );

                $entries[] = [
                    'uri' => $uri,
                    'changed' => time(),
                ];

                if (!$input->getOption('with-nodes-subpages')) {
                    continue;
                }

                $node = $nodeService->get($masterService->matchNodeId(
                    new PageMatcherContext([
                        'categoryId' => $category->categoryId,
                    ])
                ));
                $node->streams = $masterService->completeDefaultQuery(
                    $node->streams,
                    'category',
                    $category->categoryId
                );

                $entries = array_merge($entries, $this->generatePagerEntries($node, $context, $uri));
            }

            $query->cursor = $result->nextCursor;
        } while (!is_null($result->nextCursor));

        return $this->singleSitemap ? $entries : $this->renderSitemaps($context, $entries, 'categories');
    }

    private function generatePagerEntries(Node $node, Context $context, $baseUri): array
    {
        /** @var StreamService $streamService */
        $streamService = $this->getContainer()->get(StreamService::class);

        $streams = $streamService->getStreamData($node, $context);

        $entries = [];
        foreach ($streams as $streamId => $result) {
            if (false === ($result instanceof Result)) {
                continue;
            }
            if ($result->total <= $result->count) {
                continue;
            }

            $offset = $result->count;
            for ($i = 0; $i < (ceil($result->total / $result->count) - 1); ++$i) {
                $entries[] = [
                    'uri' => sprintf(
                        '%s?s[%s][offset]=%d',
                        $baseUri,
                        $streamId,
                        $offset
                    ),
                    'changed' => strtotime($node->metaData['changed']),
                ];
                $offset += $result->count;
            }
        }

        return $entries;
    }

    private function generateProductSitemap(Context $context, OutputInterface $output): array
    {
        $limit = min(500, $this->maxEntries);

        /** @var ProductSearchApi $productSearchApi */
        $productSearchApi = $this->getContainer()->get(ProductSearchApi::class);
        /** @var ProductRouter $productRouter */
        $productRouter = $this->getContainer()->get(ProductRouter::class);

        $output->writeln('Generating product sitemaps…');

        $query = new ProductQuery([
            'locale' => $context->locale,
            'limit' => $limit,
        ]);

        $entries = [];
        $fileNames = [];

        do {
            $result = $productSearchApi->query($query)->wait();

            /** @var \Frontastic\Common\ProductApiBundle\Domain\Product $product */
            foreach ($result as $product) {
                $entries[] = [
                    'uri' => $productRouter->generateUrlFor($product),
                    'changed' => $product->changed ?? time(),
                    'images' => $product->images,
                ];
            }

            if ($result->nextCursor !== null && $query->cursor === $result->nextCursor) {
                $output->writeln(sprintf(
                    '<error>Product pagination broken. Current cursor (%s) equals next cursor (%s).</error>',
                    $query->cursor,
                    $result->nextCursor
                ));
                break;
            }

            // Generates multiple sitemaps with $maxEntries products, so the process won´t break with to many entries
            if (!$this->singleSitemap && count($entries) >= $this->maxEntries) {
                $entryChunk = array_splice($entries, 0, $this->maxEntries);
                $fileName = $this->createSitemapName('products', count($fileNames));

                $this->renderSitemap($this->publicUrl, $entryChunk, $fileName);
                $fileNames[] = $fileName;
            }

            $query->cursor = $result->nextCursor;
        } while (!is_null($result->nextCursor));

        // Generate a sitemap for the remaining entries
        if (!$this->singleSitemap && count($entries) > 0) {
            $fileName = $this->createSitemapName('products', count($fileNames));
            $this->renderSitemap($this->publicUrl, $entries, $fileName);
            $fileNames[] = $fileName;
        }

        return $this->singleSitemap ? $entries : $fileNames;
    }

    private function filterEntries(array $entries): array
    {
        if (0 === count($this->excludes)) {
            return $entries;
        }

        $pattern = sprintf('(%s)', join('|', array_map('trim', $this->excludes)));
        return array_filter(
            $entries,
            function (array $entry) use ($pattern): bool {
                return (0 === preg_match($pattern, $entry['uri']));
            }
        );
    }

    private function renderSitemaps(Context $context, array $entries, string $type): array
    {
        $entries = $this->filterEntries($entries);

        $sitemaps = [];

        while (count($entries) > 0) {
            $filePath = $this->createSitemapName($type, count($sitemaps));
            $sitemaps[] = $filePath;

            $this->renderSitemap(
                $this->publicUrl,
                array_slice($entries, 0, $this->maxEntries),
                $filePath
            );
            $entries = array_slice($entries, $this->maxEntries);
        }
        return $sitemaps;
    }

    private function renderSitemap(string $publicUrl, array $entries, string $file): void
    {
        $this->render(
            $publicUrl,
            ['urls' => $entries],
            'Sitemap/sitemap.xml.twig',
            $file
        );
    }

    private function renderIndex(string $publicUrl, array $sitemaps, string $file): void
    {
        $this->render(
            $publicUrl,
            [
                'sitemaps' => array_map(
                    function ($sitemap) {
                        return ['uri' => $sitemap, 'changed' => time()];
                    },
                    $sitemaps
                ),
            ],
            'Sitemap/index.xml.twig',
            $file
        );
    }

    private function createSitemapName(string $type, int $number): string
    {
        return sprintf('sitemap_%s-%d.xml', $type, $number);
    }

    private function render(
        string $publicUrl,
        array  $data,
        string $templateFile,
        string $file,
        bool $isSiteMapIndex = false
    ): void {
        /** @var EngineInterface $template */
        $template = $this->getContainer()->get('templating');

        $data['_publicUrl'] = rtrim($publicUrl, '/');

        $this->filesystem->dumpFile(
            $this->workingDir . '/' . $file,
            $template->render($templateFile, $data)
        );
    }

    /**
     * Filters the given Routes and returns the ones that correspond to the given Node
     *
     * @param Route[] $routes
     * @param Node $node
     * @return Route[]
     */
    private function getRoutesForNode(array $routes, Node $node): array
    {
        return array_filter(
            $routes,
            function (Route $route) use ($node) {
                return $route->nodeId === $node->nodeId;
            }
        );
    }

    /**
     * Returns the Route that should be used given the current context and therefore set locale
     *
     * @param Context $context
     * @param Route[] $nodeRoutes
     * @return Route
     */
    private function getRouteForCurrentContext(Context $context, array $nodeRoutes): Route
    {
        $defaultRoute = null;

        /** @var Route $route */
        foreach ($nodeRoutes as $route) {
            if ($route->locale === $context->locale) {
                return $route;
            }

            if ($route->locale === $context->project->defaultLanguage) {
                $defaultRoute = $route;
            }
        }

        // return the defaultRoute if one is found, otherwise return the first one found.
        return $defaultRoute ?? reset($nodeRoutes);
    }

    /**
     * @param $outputDir
     */
    private function storeInFilesystem(string $outputDir): void
    {
        $backupDir = null;
        if ($this->filesystem->exists($outputDir)) {
            $backupDir = uniqid(sprintf('%s/backup_sitemap_', sys_get_temp_dir()));
            $this->filesystem->rename($outputDir, $backupDir);
        }
        $this->filesystem->rename($this->workingDir, $outputDir);

        if ($backupDir) {
            $this->filesystem->remove($backupDir);
        }
    }

    private function storeInDatabase(string $outputDir)
    {
        /** @var SitemapService $sitemapService */
        $sitemapService = $this->getContainer()->get(SitemapService::class);

        try {
            $basedir = $this->cleanBasePath($outputDir);

            $generationDate = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
            $generationTimestamp = (int)$generationDate->format('U');

            $sitemaps = [];

            $finder = new Finder();
            foreach ($finder->files()->in($this->workingDir) as $file) {
                $sitemaps[] = new Sitemap([
                    'generationTimestamp' => $generationTimestamp,
                    'basedir' => $basedir,
                    'filename' => $file->getRelativePathname(),
                    'filepath' => $basedir . $file->getRelativePathname(),
                    'content' => $file->getContents()
                ]);
            }

            $sitemapService->storeAll($sitemaps);

            $this->removeDirectoryRecursive($outputDir);
        } finally {
            $this->removeDirectoryRecursive($this->workingDir);
        }

        $sitemapService->cleanOutdated(3);
    }

    /**
     * @param $outputDir
     * @return mixed|string
     */
    protected function cleanBasePath($outputDir)
    {
        // TODO: Proper error message for other paths
        list(, $basePath) = explode('public/', $outputDir);

        if ($basePath) {
            $basePath = trim($basePath, '/') . '/';
        }
        return $basePath;
    }

    private function removeDirectoryRecursive(string $directory): void
    {
        if (!file_exists($directory)) {
            return;
        }

        $this->filesystem->remove(
            (new Finder())->in($directory)->sortByType()->reverseSorting()
        );
        $this->filesystem->remove($directory);
    }
}
