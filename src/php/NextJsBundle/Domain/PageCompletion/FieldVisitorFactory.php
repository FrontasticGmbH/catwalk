<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor\SequentialFieldVisitor;

class FieldVisitorFactory
{
    private SiteBuilderPageService $pageService;
    private NodeService $nodeService;

    public function __construct(SiteBuilderPageService $pageService, NodeService $nodeService)
    {
        $this->pageService = $pageService;
        $this->nodeService = $nodeService;
    }

    public function createVisitor(Context $context, array $tasticFieldData)
    {
        return new SequentialFieldVisitor([
            // IMPORTANT: TasticFieldHandler must be called before PageFolderUrl!
            new TasticFieldValueInlineVisitor($tasticFieldData),
            new PageFolderCompletionVisitor($this->pageService, $this->nodeService),
            new SelectTranslationVisitor($context),
        ]);
    }
}
