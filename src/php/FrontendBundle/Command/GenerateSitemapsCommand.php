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
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
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
                'max-entries',
                null,
                InputOption::VALUE_REQUIRED,
                'Maximum number of url entries in a sitemap file',
                self::MAX_ENTRIES
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->maxEntries = $input->getOption('max-entries');
        $this->workingDir = uniqid(sprintf('%s/sitemap_', sys_get_temp_dir()));

        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir($this->workingDir);

        /** @var ContextService $contextService */
        $contextService = $this->getContainer()->get(ContextService::class);

        $context = $contextService->getContext();

        $sitemaps = [];
        if ($input->getOption('all') || $input->getOption('with-nodes')) {
            $sitemaps = array_merge($sitemaps, $this->generateNodeSitemap($context, $output));
        }
        if ($input->getOption('all') || $input->getOption('with-categories')) {
            $sitemaps = array_merge($sitemaps, $this->generateCategorySitemap($context, $output));
        }
        if ($input->getOption('all') || $input->getOption('with-products')) {
            $sitemaps = array_merge($sitemaps, $this->generateProductSitemap($context, $output));
        }

        $output->writeln('Generating sitemap index…');
        $this->renderIndex($context, $sitemaps, 'sitemap_index.xml');

        $backupDir = null;
        $outputDir = $input->getArgument('output-directory');
        if ($this->filesystem->exists($outputDir)) {
            $backupDir = uniqid(sprintf('%s/backup_sitemap_', sys_get_temp_dir()));
            $this->filesystem->rename($outputDir, $backupDir);
        }
        $this->filesystem->rename($this->workingDir, $outputDir);

        if ($backupDir) {
            $this->filesystem->remove($backupDir);
        }
    }

    private function generateNodeSitemap(Context $context, OutputInterface $output): array
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
                $pageService->fetchForNode($node);
            } catch (\Exception $e) {
                continue;
            }

            $entries[] = [
                'uri' => $routes[$node->nodeId]->route,
                'changed' => strtotime($node->metaData['changed'])
            ];

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

    private function generateCategorySitemap(Context $context, OutputInterface $output): array
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
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->getContainer()->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->getContainer()->get(PageService::class);

        $page = $pageService->fetchForNode($node);
        $data = $dataProvider->fetchDataFor($node, $context, [], $page);

        $entries = [];
        foreach (get_object_vars($data->stream) as $streamId => $result) {
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
                    'changed' => time()
                ];

                foreach ($product->images as $image) {
                    $entries[] = [
                        'uri' => $image,
                        'changed' => time()
                    ];
                }
            }

            $query->offset += $limit;
        } while ($result->count > 0);

        return $this->renderSitemaps($context, $entries, 'products');
    }

    private function renderSitemaps(Context $context, array $entries, string $type): array
    {
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
