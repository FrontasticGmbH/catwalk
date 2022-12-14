<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor\SequentialFieldVisitor;
use PHPUnit\Framework\TestCase;

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
        \Phake::when($this->siteBuilderPageService)->getPathsForSiteBuilderPage($pageFolderId)->thenReturn($urls);        \Phake::when($this->fieldVisitorFactory)->createNodeDataVisitor->thenReturn(new SequentialFieldVisitor([]));
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
}
