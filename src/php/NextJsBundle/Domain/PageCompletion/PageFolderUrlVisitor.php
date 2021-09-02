<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\LinkReferenceValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderReferenceValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderTreeValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderValue;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class PageFolderUrlVisitor implements FieldVisitor
{
    private SiteBuilderPageService $pageService;

    public function __construct(SiteBuilderPageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if ($value === null) {
            return $value;
        }

        if ($configuration->getType() === 'node') {
            return $this->createNodeRepresentation($value);
        }

        if ($configuration->getType() === 'reference') {
            if (isset($value['type']) && $value['type'] === 'node') {
                return new PageFolderReferenceValue([
                    'pageFolder' => $this->createNodeRepresentation($value['target']),
                    'openInNewWindow' => (isset($value['mode']) && $value['mode'] === 'new_window'),
                ]);
            }
            if (isset($value['type']) && $value['type'] === 'link') {
                return new LinkReferenceValue([
                    'link' => $value['target'],
                    'openInNewWindow' => (isset($value['mode']) && $value['mode'] === 'new_window'),
                ]);
            }

            // TODO: Log strange unknown reference value
            return $value;
        }

        if ($configuration->getType() === 'tree') {
            return $this->generateTreeStructure($value);
        }

        return $value;
    }

    private function createNodeRepresentation(string $pageFolderId): PageFolderValue
    {
        return new PageFolderValue([
            'pageFolderId' => $pageFolderId,
            // TODO: We also need the name!
            '_urls' => $this->pageService->getPathsForSiteBuilderPage($pageFolderId),
        ]);
    }

    private function generateTreeStructure($value): ?PageFolderTreeValue
    {
        if (!isset($value['handledValue']) || !($value['handledValue'] instanceof Node)) {
            // TODO: Log!
            return null;
        }

        $requestedDepth = $value['studioValue']['depth'] ?? null;

        return $this->generateTreeRecursive($value['handledValue'], $requestedDepth);
    }

    private function generateTreeRecursive(Node $node, ?int $requestedDepth): PageFolderTreeValue
    {
        $treeValue = new PageFolderTreeValue([
            'pageFolderId' => $node->nodeId,
            'name' => $node->name,
            'requestedDepth' => $requestedDepth,
            '_urls' => $this->pageService->getPathsForSiteBuilderPage($node->nodeId),
        ]);

        foreach ($node->children as $childNode) {
            $treeValue->children[] = $this->generateTreeRecursive($childNode, $requestedDepth);
        }
        return $treeValue;
    }
}
