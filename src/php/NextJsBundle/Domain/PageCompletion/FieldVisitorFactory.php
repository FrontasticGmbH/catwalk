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

    public function createVisitor(Context $context)
    {
        return new SequentialFieldVisitor([
            new PageFolderUrlVisitor($this->pageService),
            new SelectTranslationVisitor($context),
        ]);
    }
}
