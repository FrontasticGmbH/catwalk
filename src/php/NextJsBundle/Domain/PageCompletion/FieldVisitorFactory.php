<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor\SequentialFieldVisitor;

class FieldVisitorFactory
{
    private SiteBuilderPageService $siteBuilderPageService;
    private NodeService $nodeService;
    private PageService $pageService;

    private ?FieldVisitor $nodeDataVisitor = null;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        NodeService $nodeService,
        PageService $pageService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
    }

    public function createTasticDataVisitor(Context $context, array $tasticFieldData): FieldVisitor
    {
        return new SequentialFieldVisitor([
            // IMPORTANT: TasticFieldHandler must be called before PageFolderUrl!
            new TasticFieldValueInlineVisitor($tasticFieldData),
            new SelectTranslationVisitor($context),
            new PageFolderCompletionVisitor(
                $this->siteBuilderPageService,
                $this->nodeService,
                $this->pageService,
                $context,
                $this
            ),
            new DataSourceReferenceFormatUpdater(),
        ]);
    }

    public function createNodeDataVisitor(Context $context): FieldVisitor
    {
        if ($this->nodeDataVisitor === null) {
            $this->nodeDataVisitor = new SequentialFieldVisitor([
                new SelectTranslationVisitor($context),
                new PageFolderCompletionVisitor(
                    $this->siteBuilderPageService,
                    $this->nodeService,
                    $this->pageService,
                    $context,
                    $this
                ),
            ]);
        }
        return $this->nodeDataVisitor;
    }

    public function createProjectConfigurationDataVisitor(Context $context): FieldVisitor
    {
        return new SequentialFieldVisitor([
            new SelectTranslationVisitor($context),
        ]);
    }
}
