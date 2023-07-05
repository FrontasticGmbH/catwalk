<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Schema;
use Frontastic\Catwalk\FrontendBundle\Domain\SchemaService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Project;
use Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderTreeValue;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor\SequentialFieldVisitor;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\CacheItemPoolInterface;

class PageFolderCompletionVisitorTest extends TestCase
{

    private PageFolderCompletionVisitor $subject;

    private SiteBuilderPageService $siteBuilderPageService;
    private NodeService $nodeService;
    private PageService $pageService;
    private Context $context;
    private FieldVisitorFactory $fieldVisitorFactory;

    private FieldConfiguration $fieldConfiguration;


    public function setUp(): void
    {
        $this->siteBuilderPageService = \Phake::mock(SiteBuilderPageService::class);
        $this->fieldConfiguration = \Phake::mock(FieldConfiguration::class);
        $this->nodeService = \Phake::mock(NodeService::class);
        $this->pageService = \Phake::mock(PageService::class);
        $this->context = new Context();
        $this->fieldVisitorFactory = \Phake::mock(FieldVisitorFactory::class);

        $this->subject = new PageFolderCompletionVisitor(
            $this->siteBuilderPageService,
            $this->nodeService,
            $this->pageService,
            $this->context,
            $this->fieldVisitorFactory
        );
    }

    public function testNodeSuccessful()
    {

        $pageFolderId = 'dummyPageFolderId';
        $nodeName = 'nodeName';
        $nodeConfiguration = (object)['configuration' => 'helloWorld'];
        $urls = [
            "de_CH" => "/dummy/pagede",
            "fr_CH" => "/dummy/pagefr",
            "it_CH" => "/dummy/pageit",
            "de_LI" => "/dummy/pagede2"
        ];


        $node = \Phake::mock(Node::class);
        $node->name = $nodeName;
        $node->configuration = $nodeConfiguration;

        $page = \Phake::mock(Page::class);

        \Phake::when($this->fieldConfiguration)->getType->thenReturn('node');
        \Phake::when($this->nodeService)->get->thenReturn($node);
        \Phake::when($this->nodeService)->completeCustomNodeData->thenReturn($node);
        \Phake::when($this->pageService)->fetchForNode->thenReturn($page);
        \Phake::when($this->siteBuilderPageService)->getPathsForSiteBuilderPage($pageFolderId)->thenReturn($urls);
        \Phake::when($this->fieldVisitorFactory)->createOneLevelNodeDataVisitor->thenReturn(new SequentialFieldVisitor([]));
        $this->context->locale = 'it_CH';

        $result = $this->subject->processField(
            $this->fieldConfiguration,
            $pageFolderId,
            []
        );

        $this->assertNotNull($result);
        $this->assertEquals($pageFolderId, $result->pageFolderId);
        $this->assertEquals($nodeName, $result->name);
        $this->assertEquals($nodeConfiguration, $result->configuration);
        $this->assertEquals($urls, $result->_urls);
        $this->assertEquals('/dummy/pageit', $result->_url);
    }

    public function testEmptyTreeDoesntError()
    {
        \Phake::when($this->fieldConfiguration)->getType->thenReturn('tree');

        $result = $this->subject->processField(
            $this->fieldConfiguration,
            [
                "studioValue" => [
                    'node' => null,
                    'depth' => 0
                ],
                'handledValue' => new Node()
            ],
            []
        );

        $this->assertNull($result);
    }

    public function testDepthIsStringDoesntError()
    {
        $nodeName = 'nodeName';
        $nodeConfiguration = (object)['configuration' => 'helloWorld'];

        $node = new Node(["nodeId" => "test"]);
        $node->name = $nodeName;
        $node->configuration = $nodeConfiguration;

        \Phake::when($this->fieldConfiguration)->getType->thenReturn('tree');
        \Phake::when($this->fieldVisitorFactory)->createNodeDataVisitor->thenReturn(new SequentialFieldVisitor([]));
        \Phake::when($this->nodeService)->completeCustomNodeData->thenReturn($node);

        $result = $this->subject->processField(
            $this->fieldConfiguration,
            [
                "studioValue" => [
                    'node' => $node,
                    'depth' => "test"
                ],
                'handledValue' => $node
            ],
            []
        );

        $this->assertInstanceOf(PageFolderTreeValue::class, $result);
        $this->assertNull($result->requestedDepth);
    }

    public function testDepthIsStringWithEmptyNodeDoesntError()
    {
        \Phake::when($this->fieldConfiguration)->getType->thenReturn('tree');

        $result = $this->subject->processField(
            $this->fieldConfiguration,
            [
                "studioValue" => [
                    'node' => null,
                    'depth' => "test"
                ],
                'handledValue' => new Node()
            ],
            []
        );

        $this->assertNull($result);
    }

    public function testDepthIsNumericStringDoesntError()
    {
        $nodeName = 'nodeName';
        $nodeConfiguration = (object)['configuration' => 'helloWorld'];

        $node = new Node(["nodeId" => "test"]);
        $node->name = $nodeName;
        $node->configuration = $nodeConfiguration;

        \Phake::when($this->fieldConfiguration)->getType->thenReturn('tree');
        \Phake::when($this->fieldVisitorFactory)->createNodeDataVisitor->thenReturn(new SequentialFieldVisitor([]));
        \Phake::when($this->nodeService)->completeCustomNodeData->thenReturn($node);

        $result = $this->subject->processField(
            $this->fieldConfiguration,
            [
                "studioValue" => [
                    'node' => $node,
                    'depth' => "1"
                ],
                'handledValue' => $node
            ],
            []
        );

        $this->assertInstanceOf(PageFolderTreeValue::class, $result);
        $this->assertEquals(1, $result->requestedDepth);
    }

    public function testNoInfiniteLoop()
    {
        $leafNode = new Node([
            'nodeId' => 'LeafNode',
            'name' => 'LeafNode',
            'path' => '/level1/leaf',
            'depth' => 2,
            'configuration' => [
                'path' => 'leafnode', // this is the lowercased name of the node
                'pathTranslations' => [],
                'ctaReference' => 'LeafNode', // reference to node id, this provokes the infinite loop
                'openAsNewPage' => true,
            ],
        ]);

        // The schema has to match the node configuration. Especially the ctaReference field is important.
        $nodeSchema = new Schema(
            [
                'schemaId' => 'testSchema',
                'schemaType' => 'nodeConfiguration',
                'schema' => [
                    'schema' =>
                    [
                        [
                            'name' => 'Teaser Tile Preview Data',
                            'fields' => [
                                [
                                    'label' => 'Link',
                                    'field' => 'ctaReference',
                                    'translatable' => false,
                                    'required' => false,
                                    'type' => 'node',
                                    'default' => NULL,
                                ]
                            ]
                        ]
                    ]
                ],
            ]
        );

        // make sure the node schema is used in the NodeService. For this we need to partially mock the SchemaService.
        // (It would be easier to just mock the getNodeSchema method of the SchemaService, but this is a private method.)
        $tenantCache = \Phake::mock(CacheInterface::class);
        \Phake::when($tenantCache)->get(\Phake::anyParameters())->thenReturn($nodeSchema);

        $schemaService = \Phake::partialMock(SchemaService::class, \Phake::mock(SchemaGateway::class), $tenantCache);
        $nodeGateway = \Phake::mock(NodeGateway::class);

        $this->context->project = new Project(['projectId' => 'testProject', 'name' => 'testProject']);
        $contextService = \Phake::mock(ContextService::class);
        \Phake::when($contextService)->getContext()->thenReturn($this->context);

        // we need to return the right nodes without actually loading them from the database, so mock the NodeService
        $this->nodeService = \Phake::partialMock(NodeService::class, $nodeGateway, \Phake::mock(PageService::class), \Phake::mock(RouteService::class), $schemaService, $contextService);

        \Phake::when($this->fieldConfiguration)->getType->thenReturn('tree');
        \Phake::when($this->fieldConfiguration)->getField->thenReturn('pageLink');
        \Phake::when($this->fieldConfiguration)->getDefault->thenReturn(null);
        \Phake::when($this->fieldConfiguration)->isTranslatable->thenReturn(false);

        \Phake::when($this->nodeService)->get('LeafNode')->thenReturn($leafNode);

        $this->fieldVisitorFactory = \Phake::partialMock(
            FieldVisitorFactory::class,
            $this->siteBuilderPageService,
            $this->nodeService,
            $this->pageService
        );

        $subject = $this->fieldVisitorFactory->createNodeDataVisitor($this->context);

        $value =
            [
                "studioValue" => [
                    'node' => $leafNode,
                    'depth' => "2"
                ],
                'handledValue' => $leafNode
            ];
        $fieldPath = [];

        $result = $subject->processField(
            $this->fieldConfiguration,
            $value,
            $fieldPath
        );

        $this->assertNotEmpty($result);

        // root node is requested once for resolving the ctaReference
        \Phake::verify($this->nodeService, \Phake::atLeast(1))->get('LeafNode');
        \Phake::verify($this->nodeService, \Phake::atLeast(1))->completeCustomNodeData(\Phake::anyParameters());
        \Phake::verifyNoOtherInteractions($this->nodeService);

        \Phake::verify($this->fieldVisitorFactory, \Phake::atLeast(1))->createNodeDataVisitor(\Phake::anyParameters());
        \Phake::verify($this->fieldConfiguration, \Phake::atLeast(1))->getType();
    }
}
