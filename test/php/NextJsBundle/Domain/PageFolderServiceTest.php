<?php

namespace Frontastic\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderTreeNode;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\PageDataCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\PageFolderService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\TestCase;

class PageFolderServiceTest extends TestCase
{
    private PageFolderService $pageFolderService;
    private SiteBuilderPageService $siteBuilderPageServiceMock;
    private NodeService $nodeServiceMock;
    private FromFrontasticReactMapper $mapperMock;
    private RouteService $routeServiceMock;
    private PageDataCompletionService $completionServiceMock;

    private Context $contextFixture;

    public function setUp(): void
    {
        $this->contextFixture = new Context([
            'project' => new Project([
                'languages' => ['en_US'],
                'defaultLanguage' => 'en_US',
            ]),
            'customer' => new Customer([
                'name' => 'demo'
            ])
        ]);

        $this->siteBuilderPageServiceMock = \Phake::mock(SiteBuilderPageService::class);
        $this->nodeServiceMock =  \Phake::mock(NodeService::class);
        $this->mapperMock = new FromFrontasticReactMapper();
        $this->routeServiceMock =  \Phake::mock(RouteService::class);
        $this->completionServiceMock =  \Phake::mock(PageDataCompletionService::class);

        $this->pageFolderService = new PageFolderService(
            $this->siteBuilderPageServiceMock,
            $this->nodeServiceMock,
            $this->mapperMock,
            $this->routeServiceMock,
            $this->completionServiceMock
        );
    }

    public function testGetTree()
    {
        /** @var Route[] $routes */
        $routes = require __DIR__ . '/_fixtures/routes_fixture.php';

        $locale = 'en_US';
        $depth = 1;
        $path = $routes[0]->route;
        $nodeId = $routes[0]->nodeId;

        $nodes = [
            new Node([
                'nodeId' => $nodeId,
                'path' => $path,
                'name' => 'Node',
            ])
        ];

        \Phake::when($this->siteBuilderPageServiceMock)
            ->matchSiteBuilderPage(\Phake::anyParameters())
            ->thenReturn($nodeId);

        \Phake::when($this->nodeServiceMock)
            ->getNodes(\Phake::anyParameters())
            ->thenReturn($nodes);

        \Phake::when($this->routeServiceMock)
            ->generateRoutes(\Phake::anyParameters())
            ->thenReturn($routes);

        \Phake::when($this->completionServiceMock)
            ->completePageFolderData(\Phake::anyParameters());

        $tree = $this->pageFolderService->getTree($this->contextFixture, $locale, $depth, $path);

        $this->assertIsArray($tree);
        $this->assertContainsOnly(PageFolderTreeNode::class, $tree);
    }

    public function testGetEmptyTree()
    {
        /** @var Route[] $routes */
        $routes = require __DIR__ . '/_fixtures/routes_fixture.php';

        $locale = 'en_US';
        $depth = 1;

        $nodes = [
            new Node([
                'nodeId' => $routes[0]->nodeId . '_FakeNode',
                'name' => 'Node',
            ])
        ];

        \Phake::when($this->nodeServiceMock)
            ->getNodes(\Phake::anyParameters())
            ->thenReturn($nodes);

        \Phake::when($this->routeServiceMock)
            ->generateRoutes(\Phake::anyParameters())
            ->thenReturn($routes);

        $tree = $this->pageFolderService->getTree($this->contextFixture, $locale, $depth);

        $this->assertIsArray($tree);
        $this->assertEmpty($tree);
    }
}
