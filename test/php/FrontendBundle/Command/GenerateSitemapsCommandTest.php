<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\FrontendBundle\Domain\SchemaService;
use Frontastic\Catwalk\FrontendBundle\Domain\Sitemap;
use Frontastic\Catwalk\FrontendBundle\Domain\SitemapService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway;
use Frontastic\Catwalk\FrontendBundle\Gateway\SitemapGateway;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Catwalk\IntegrationTestCase;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class GenerateSitemapsCommandTest extends IntegrationTestCase
{
    private const CATEGORY_MASTER_NODE_ID = 'category-master-node';

    private const CATEGORY_MASTER_NODE_STREAMS = [[
        'streamId' => '__master',
        'name' => 'Products in Category',
        'type' => 'product-list',
        'configuration' => ['category' => null],
    ]];

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array<string>
     */
    private $removeFilesOnTearDown = [];

    /**
     * @var array<string|null>
     */
    private $categoriesSeenByStreams = [];

    public function testEmptySitemapIsGenerated()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'empty');
    }

    public function testGeneratesSitemapForSingleNode()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();
        $this->givenTheNodeAndPageFixture('singleNode');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'singleNode');
    }

    public function testGeneratesSitemapForNodeTree()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();
        $this->givenTheNodeAndPageFixture('nodeTree');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'nodeTree');
    }

    public function testGeneratesSitemapOverridePublicUrl()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();
        $this->givenTheNodeAndPageFixture('nodeTree');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
            '--base-url' => 'http://custom.site.example.com',
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'overridePublicUrl');
    }

    public function testGeneratesSitemapSingleSitemap()
    {
        $commandTester = $this->givenAGenerateSitemapsCommandTester();
        $outputDirectory = $this->givenAnOutputDirectory();
        $this->givenTheNodeAndPageFixture('nodeTree');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-nodes' => true,
            '--with-nodes-subpages' => true,
            '--with-extensions' => true,
            '--single-sitemap' => true,
        ]);

        $this->assertTheOutputDirectoryMatchesTheFixture($outputDirectory, 'singleSitemap');
    }

    public function testCategorySubpagesKeepTheStoredMasterNodeUnchanged()
    {
        $this->givenACategoryMasterNode();
        $commandTester = $this->givenAGenerateSitemapsCommandTesterForCategories(['category-1', 'category-2']);
        $outputDirectory = $this->givenAnOutputDirectory();
        putenv('database_sitemaps=1');

        $commandTester->execute([
            'output-directory' => $outputDirectory,
            '--with-categories' => true,
            '--with-nodes-subpages' => true,
            '--base-url' => 'https://example.com',
        ]);

        $this->assertNotNull($this->givenASitemapService()->loadLatestByPath('sitemap_index.xml'));
        $this->assertSame(self::CATEGORY_MASTER_NODE_STREAMS, $this->loadStoredCategoryMasterNode()->streams);
    }

    public function testCategorySubpagesUseTheStreamsOfTheirOwnCategory()
    {
        $this->givenACategoryMasterNode();
        $commandTester = $this->givenAGenerateSitemapsCommandTesterForCategories(['category-1', 'category-2']);

        $commandTester->execute([
            'output-directory' => $this->givenAnOutputDirectory(),
            '--with-categories' => true,
            '--with-nodes-subpages' => true,
            '--base-url' => 'https://example.com',
        ]);

        $this->assertSame(['category-1', 'category-2'], $this->categoriesSeenByStreams);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->filesystem = new Filesystem();
    }

    protected function tearDown(): void
    {
        putenv('database_sitemaps');
        $this->filesystem->remove($this->removeFilesOnTearDown);
        $this->removeFilesOnTearDown = [];

        parent::tearDown();
    }

    private function givenAGenerateSitemapsCommandTester(): CommandTester
    {
        $command = self::getContainer()->get(GenerateSitemapsCommand::class);

        return new CommandTester($command);
    }

    /**
     * @param array<string> $categoryIds
     */
    private function givenAGenerateSitemapsCommandTesterForCategories(array $categoryIds): CommandTester
    {
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $contextService = $this->createMock(ContextService::class);
        $contextService->method('getContext')->willReturn(new Context(['locale' => 'en_GB']));

        $productApi = $this->createMock(ProductApi::class);
        $productApi->method('queryCategories')->willReturn(new Result([
            'items' => array_map(
                fn (string $categoryId) => new Category(['categoryId' => $categoryId, 'name' => $categoryId]),
                $categoryIds
            ),
        ]));

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturnCallback(
            fn (string $route, array $parameters) => '/category/' . $parameters['id']
        );

        $masterService = $this->createPartialMock(MasterService::class, ['matchNodeId']);
        $masterService->method('matchNodeId')->willReturn(self::CATEGORY_MASTER_NODE_ID);

        $streamService = $this->createMock(StreamService::class);
        $streamService->method('getStreamData')->willReturnCallback(function (Node $node): array {
            $this->categoriesSeenByStreams[] = $node->streams[0]['configuration']['category'];
            return [];
        });

        $command = new GenerateSitemapsCommand(
            new Environment(new FilesystemLoader(__DIR__ . '/../../../templates')),
            new Filesystem(),
            $contextService,
            $this->givenASitemapService(),
            new NodeService(
                new NodeGateway($entityManager->getRepository(Node::class), $entityManager),
                $this->createMock(PageService::class),
                $this->createMock(RouteService::class),
                $this->createMock(SchemaService::class),
                $contextService
            ),
            $this->createMock(RouteService::class),
            $this->createMock(PageService::class),
            $productApi,
            $urlGenerator,
            $masterService,
            $streamService,
            $this->createMock(ProductSearchApi::class),
            $this->createMock(ProductRouter::class)
        );

        return new CommandTester($command);
    }

    private function givenASitemapService(): SitemapService
    {
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        return new SitemapService(
            new SitemapGateway($entityManager->getRepository(Sitemap::class), $entityManager),
            []
        );
    }

    private function givenACategoryMasterNode(): void
    {
        self::getContainer()->get(NodeService::class)->replicate([[
            'nodeId' => self::CATEGORY_MASTER_NODE_ID,
            'sequence' => '1',
            'isMaster' => true,
            'configuration' => [],
            'streams' => self::CATEGORY_MASTER_NODE_STREAMS,
            'name' => 'Category',
            'path' => [],
            'sort' => 0,
            'metaData' => ['changed' => '2026-10-08T00:00:00+00:00'],
            'isDeleted' => false,
        ]]);
        self::getContainer()->get('doctrine.orm.entity_manager')->clear();
    }

    private function loadStoredCategoryMasterNode(): Node
    {
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->clear();

        return (new NodeGateway($entityManager->getRepository(Node::class), $entityManager))
            ->get(self::CATEGORY_MASTER_NODE_ID);
    }

    private function givenAnOutputDirectory(): string
    {
        $tempDirectory = $this->givenATempDirectory();
        $outputDirectory = $tempDirectory . '/public/';
        $this->filesystem->mkdir($outputDirectory);
        return $outputDirectory;
    }

    private function givenATempDirectory(): string
    {
        $path = sys_get_temp_dir() . '/' . uniqid('frontastic.test.', true);
        $this->filesystem->mkdir($path, 0700);
        $this->removeFilesOnTearDown[] = $path;
        return $path;
    }

    private function assertTheOutputDirectoryMatchesTheFixture(string $outputDirectory, string $fixtureName): void
    {
        $fixtureDirectory = __DIR__ . '/_fixtures/sitemaps/' . $fixtureName;

        $this->assertDirectoryExists($fixtureDirectory, 'unknown fixture ' . $fixtureName);
        $this->assertDirectoryExists($outputDirectory, 'the output directory does not exist');

        $finder = new Finder();
        $fixtureIterator = $finder->files()->in($fixtureDirectory);
        foreach ($fixtureIterator as $fixtureFile) {
            $fixtureFilename = $fixtureFile->getRealPath();
            $outputFilename = $outputDirectory . '/' . $fixtureFile->getRelativePathname();

            $this->assertFileExists($outputFilename);

            $fixtureContent = file_get_contents($fixtureFilename);
            $fixtureContent = str_replace('__CURRENT_DATE__', date('Y-m-d'), $fixtureContent);

            $outputContent = file_get_contents($outputFilename);
            $this->assertEquals($fixtureContent, $outputContent);
        }
    }

    private function givenTheNodeAndPageFixture(string $fixtureName): void
    {
        $nodeFilename = __DIR__ . '/_fixtures/nodes/' . $fixtureName . '.json';
        $this->assertFileIsReadable($nodeFilename, 'Unknown node fixture: ' . $fixtureName);

        $pageFilename = __DIR__ . '/_fixtures/pages/' . $fixtureName . '.json';
        $this->assertFileIsReadable($nodeFilename, 'Unknown page fixture: ' . $pageFilename);

        $container = self::getContainer();
        $container->get(NodeService::class)->replicate(json_decode(file_get_contents($nodeFilename), true));
        $container->get(PageService::class)->replicate(json_decode(file_get_contents($pageFilename), true));
    }
}
