<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
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
    private SiteBuilderPageService $siteBuilderPageService;
    private NodeService $nodeService;
    private Context $context;
    private FieldVisitorFactory $fieldVisitorFactory;
    private PageService $pageService;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        NodeService $nodeService,
        PageService $pageService,
        Context $context,
        FieldVisitorFactory $fieldVisitorFactory
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
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
                } catch (\Throwable $e) {
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

        $urls = $this->siteBuilderPageService->getPathsForSiteBuilderPage($pageFolderId);

        return new PageFolderValue([
            'pageFolderId' => $pageFolderId,
            'name' => $node->name,
            'configuration' => (object)$node->configuration,
            'hasLivePage' => $this->pageExists($pageFolderId),
            '_urls' => $urls,
            '_url' => LocalizedValuePicker::getValueForCurrentLocale($this->context, $urls)
        ]);
    }

    private function generateTreeStructure($value): ?PageFolderTreeValue
    {
        if (!isset($value['handledValue'])
            || !($value['handledValue'] instanceof Node)
            || !isset($value['handledValue']->nodeId)
        ) {
            // TODO: Log!
            var_dump($value);
            die();
            return null;
        }

        $requestedDepth = $value['studioValue']['depth'] ?? null;
        
        if (!is_int($requestedDepth) && is_numeric($requestedDepth)) {
            $requestedDepth = intval($requestedDepth);
        } elseif (!is_int($requestedDepth) && $requestedDepth !== null) {
            $requestedDepth = null;
        }
        
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
            'hasLivePage' => $this->pageExists($node->nodeId),
            'requestedDepth' => $requestedDepth,
            '_urls' => $this->siteBuilderPageService->getPathsForSiteBuilderPage($node->nodeId),
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

    private function pageExists(string $pageFolderId)
    {
        try {
            $page = $this->pageService->fetchForNode(new Node(["nodeId" => $pageFolderId]), $this->context);
            return $page != null;
        } catch (\Exception $e) {
            // Page does not exist, do nothing
        }

        return false;
    }
}
