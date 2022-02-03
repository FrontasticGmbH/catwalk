<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\Error;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\LinkReferenceValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderReferenceValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderTreeValue;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue\PageFolderValue;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class PageFolderCompletionVisitor implements FieldVisitor
{
    use LocaleVisitorTrait;

    private SiteBuilderPageService $pageService;
    private NodeService $nodeService;
    private Context $context;
    private FieldVisitorFactory $fieldVisitorFactory;

    public function __construct(
        SiteBuilderPageService $pageService,
        NodeService $nodeService,
        Context $context,
        FieldVisitorFactory $fieldVisitorFactory
    ) {
        $this->pageService = $pageService;
        $this->nodeService = $nodeService;
        $this->context = $context;
        $this->fieldVisitorFactory = $fieldVisitorFactory;
    }

    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if ($value === null) {
            return $value;
        }

        if ($configuration->getType() === 'node') {
            try {
                return $this->createNodeRepresentation($value);
            } catch (\Exception $e) {
                return $this->createPageFolderNotFoundError($value);
            }
        }

        if ($configuration->getType() === 'reference') {
            if (isset($value['type']) && $value['type'] === 'node') {
                try {
                    $pageFolderRepresentation = $this->createNodeRepresentation($value['target']);
                } catch (\Exception $e) {
                    return $this->createPageFolderNotFoundError($value['target']);
                }
                return new PageFolderReferenceValue([
                    'pageFolder' => $pageFolderRepresentation,
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
        $node = $this->nodeService->get($pageFolderId);
        $node = $this->nodeService->completeCustomNodeData(
            $node,
            $this->fieldVisitorFactory->createNodeDataVisitor($this->context)
        );

        $urls = $this->pageService->getPathsForSiteBuilderPage($pageFolderId);

        return new PageFolderValue([
            'pageFolderId' => $pageFolderId,
            'name' => $node->name,
            'configuration' => (object)$node->configuration,
            '_urls' => $urls,
            '_url' => $this->getValueForCurrentLocale($urls)
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
        $node = $this->nodeService->completeCustomNodeData(
            $node,
            $this->fieldVisitorFactory->createNodeDataVisitor($this->context)
        );

        $treeValue = new PageFolderTreeValue([
            'pageFolderId' => $node->nodeId,
            'name' => $node->name,
            'configuration' => (object)$node->configuration,
            'requestedDepth' => $requestedDepth,
            '_urls' => $this->pageService->getPathsForSiteBuilderPage($node->nodeId),
        ]);

        foreach ($node->children as $childNode) {
            $treeValue->children[] = $this->generateTreeRecursive($childNode, $requestedDepth);
        }
        return $treeValue;
    }

    /**
     * @return Error
     */
    private function createPageFolderNotFoundError(?string $pageFolderId): Error
    {
        return new Error([
            'message' => sprintf(
                'Referenced page folder with ID "%s" could not be found.',
                $pageFolderId ?? 'UNKNOWN'
            ),
            'errorCode' => Error::ERROR_CODE_PAGE_FOLDER_NOT_FOUND,
            'developerHint' => '// TODO: Docs link',
        ]);
    }
}
