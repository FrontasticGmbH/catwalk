<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use PHPUnit\Framework\TestCase;

class PageFolderCompletionVisitorTest extends TestCase
{

    private PageFolderCompletionVisitor $subject;

    private SiteBuilderPageService $pageService;
    private NodeService $nodeService;
    private Context $context;
    private FieldVisitorFactory $fieldVisitorFactory;

    private FieldConfiguration $fieldConfiguration;


    public function setUp()
    {
        $this->pageService = \Phake::mock(SiteBuilderPageService::class);
        $this->fieldConfiguration = \Phake::mock(FieldConfiguration::class);
        $this->nodeService = \Phake::mock(NodeService::class);
        $this->context = new Context();
        $this->fieldVisitorFactory = \Phake::mock(FieldVisitorFactory::class);

        $this->subject = new PageFolderCompletionVisitor(
            $this->pageService, $this->nodeService, $this->context, $this->fieldVisitorFactory
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

        \Phake::when($this->fieldConfiguration)->getType->thenReturn('node');
        \Phake::when($this->nodeService)->get->thenReturn($node);
        \Phake::when($this->nodeService)->completeCustomNodeData->thenReturn($node);
        \Phake::when($this->pageService)->getPathsForSiteBuilderPage($pageFolderId)->thenReturn($urls);
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
