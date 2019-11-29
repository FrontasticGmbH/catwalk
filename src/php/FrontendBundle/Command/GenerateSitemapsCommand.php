<?php
namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\FrontendBundle\Domain\SitemapService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
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
     * @var string[]
     */
    private $excludes = [];

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
                InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY,
                'Pattern to exclude all urls that match'
            );
    }

    /**
     * @SuppressWarnings(PHPMD.CyclomaticComplexity) Batch code
     * @SuppressWarnings(PHPMD.NPathComplexity) Batch code
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->maxEntries = $input->getOption('max-entries');
        $this->workingDir = uniqid(sprintf('%s/sitemap_', sys_get_temp_dir()));
        $this->excludes = $input->getOption('exclude');
        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($this->workingDir);

        /** @var ContextService $contextService */
        $contextService = $this->getContainer()->get(ContextService::class);

        $context = $contextService->getContext();

        $sitemaps = [];
        if ($input->getOption('all') || $input->getOption('with-nodes')) {
            $sitemaps = array_merge($sitemaps, $this->generateNodeSitemap($context, $input, $output));
        }
        if ($input->getOption('all') || $input->getOption('with-categories')) {
            $sitemaps = array_merge($sitemaps, $this->generateCategorySitemap($context, $input, $output));
        }
        if ($input->getOption('all') || $input->getOption('with-products')) {
            $sitemaps = array_merge($sitemaps, $this->generateProductSitemap($context, $output));
        }
        if ($input->getOption('all') || $input->getOption('with-extensions')) {
            $sitemaps = array_merge($sitemaps, $this->generateSitemapExtensions($context, $output));
        }

        $outputDir = $input->getArgument('output-directory');
        list(, $basePath) = explode('public/', $outputDir);
        if ($basePath) {
            $basePath = trim($basePath, '/') . '/';
        }

        $sitemaps = array_map(function ($sitemap) use ($basePath) {
            return $basePath . $sitemap;
        }, $sitemaps);

        $output->writeln('Generating sitemap index…');
        $this->renderIndex($context, $sitemaps, 'sitemap_index.xml');

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

    private function generateSitemapExtensions(Context $context, OutputInterface $output): array
    {
        /** @var SitemapService $sitemapService */
        $sitemapService = $this->getContainer()->get(SitemapService::class);

        $sitemaps = [];
        foreach ($sitemapService->getExtensions() as $extension) {
            $entries = [];
            foreach ($extension->getEntries() as $entry) {
                if (!isset($entry['uri'])) {
                    throw new \DomainException('uri needs to be set for entries returned by extension point!');
                }

                if (!isset($entry['changed'])) {
                    $entry['changed'] = time();
                }

                $entries[] = $entry;
            }

            $output->writeln("Generating {$extension->getName()} sitemaps…");

            $sitemaps = array_merge($sitemaps, $this->renderSitemaps($context, $entries, $extension->getName()));
        }

        return $sitemaps;
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

            if (!isset($routes[$node->nodeId])) {
                // there is no route for this node, maybe some parent nodes in the tree of this node has been deleted.
                continue;
            }

            $entries[] = [
                'uri' => $routes[$node->nodeId]->route,
                'changed' => strtotime($node->metaData['changed'])
            ];

            if (!$input->getOption('with-nodes-subpages')) {
                continue;
            }
            $entries = array_merge(
                $entries,
                $this->generatePagerEntries(
                    $node,
                    $context,
                    $routes[$node->nodeId]->route
                )
            );
        }

        return $this->renderSitemaps($context, $entries, 'nodes');
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
            'offset' => 0,
            'limit' => $limit,
        ]);

        $entries = [];

        do {
            /** @var \Frontastic\Common\ProductApiBundle\Domain\Category[] $result */
            $result = $productApi->getCategories($query);

            foreach ($result as $category) {
                $uri = $urlGenerator->generate(
                    'Frontastic.Frontend.Master.Category.view',
                    [
                        'id' => $category->categoryId,
                        'slug' => strtolower(rawurlencode($category->name))
                    ]
                );

                $entries[] = [
                    'uri' => $uri,
                    'changed' => time()
                ];

                if (!$input->getOption('with-nodes-subpages')) {
                    continue;
                }

                $node = $nodeService->get($masterService->matchNodeId(
                    new PageMatcherContext([
                        'categoryId' => $category->categoryId
                    ])
                ));
                $node->streams = $masterService->completeDefaultQuery(
                    $node->streams,
                    'category',
                    $category->categoryId
                );

                $entries = array_merge($entries, $this->generatePagerEntries($node, $context, $uri));
            }

            $query->offset += $limit;
        } while (count($result) > 0);

        return $this->renderSitemaps($context, $entries, 'categories');
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
                    'changed' => strtotime($node->metaData['changed'])
                ];
                $offset += $result->count;
            }
        }

        return $entries;
    }

    private function generateProductSitemap(Context $context, OutputInterface $output): array
    {
        $limit = min(500, $this->maxEntries);

        /** @var ProductApi $productApi */
        $productApi = $this->getContainer()->get(ProductApi::class);
        /** @var ProductRouter $productRouter */
        $productRouter = $this->getContainer()->get(ProductRouter::class);

        $output->writeln('Generating product sitemaps…');

        $query = new ProductQuery([
            'locale' => $context->locale,
            'offset' => 0,
            'limit' => $limit,
        ]);

        $entries = [];

        do {
            $result = $productApi->query($query);

            /** @var \Frontastic\Common\ProductApiBundle\Domain\Product $product */
            foreach ($result as $product) {
                $entries[] = [
                    'uri' => $productRouter->generateUrlFor($product),
                    'changed' => time(),
                    'images' => $product->images
                ];
            }

            $query->offset += $limit;
        } while ($result->count > 0);

        return $this->renderSitemaps($context, $entries, 'products');
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
            $sitemaps[] = sprintf('sitemap_%s-%d.xml', $type, count($sitemaps));

            $this->renderSitemap(
                $context,
                array_slice($entries, 0, $this->maxEntries),
                end($sitemaps)
            );
            $entries = array_slice($entries, $this->maxEntries);
        }
        return $sitemaps;
    }

    private function renderSitemap(Context $context, array $entries, string $file): void
    {
        $this->render(
            $context,
            ['urls' => $entries],
            'Sitemap/sitemap.xml.twig',
            $file
        );
    }

    private function renderIndex(Context $context, array $sitemaps, string $file): void
    {
        $this->render(
            $context,
            ['sitemaps' => array_map(
                function ($sitemap) {
                    return ['uri' => $sitemap, 'changed' => time()];
                },
                $sitemaps
            )],
            'Sitemap/index.xml.twig',
            $file
        );
    }

    private function render(Context $context, array $data, string $templateFile, string $file): void
    {
        /** @var EngineInterface $template */
        $template = $this->getContainer()->get('templating');

        $data['_publicUrl'] = rtrim($context->project->publicUrl, '/');

        $this->filesystem->dumpFile(
            $this->workingDir . '/' . $file,
            $template->render($templateFile, $data)
        );
    }
}
