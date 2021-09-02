<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor\SequentialFieldVisitor;

class FieldVisitorFactory
{
    private SiteBuilderPageService $pageService;

    public function __construct(SiteBuilderPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function createVisitor(Context $context, array $tasticFieldData)
    {
        return new SequentialFieldVisitor([
            // IMPORTANT: TasticFieldHandler must be called before PageFolderUrl!
            new TasticFieldValueInlineVisitor($tasticFieldData),
            new PageFolderUrlVisitor($this->pageService),
            new SelectTranslationVisitor($context),
        ]);
    }
}
