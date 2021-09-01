<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class NodeUrlVisitor implements FieldVisitor
{
    private SiteBuilderPageService $pageService;

    public function __construct(SiteBuilderPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function processField(FieldConfiguration $configuration, $value)
    {
        if ($value === null) {
            return;
        }

        if ($configuration->getType() === 'node') {
            return $this->createNodeRepresentation($value);
        }
        if ($configuration->getType() === 'reference' && isset($value['type']) && $value['type'] === 'node') {
            $value['target'] = $this->createNodeRepresentation($value['target']);
            return $value;
        }

        return $value;
    }

    private function createNodeRepresentation(string $pageFolderId)
    {
        return [
            'pageFolderId' => $pageFolderId,
            'urls' => $this->pageService->getPathsForSiteBuilderPage($pageFolderId),
        ];
    }
}
