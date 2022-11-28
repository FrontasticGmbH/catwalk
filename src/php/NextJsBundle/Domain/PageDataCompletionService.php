<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\Tastic as TasticInstance;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic as TasticDefinition;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion\FieldVisitorFactory;
use Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion\NodeReferenceGetterVisitor;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;

class PageDataCompletionService
{
    private TasticService $tasticService;
    private NodeService $nodeService;
    private FieldVisitorFactory $fieldVisitorFactory;

    public function __construct(
        TasticService $tasticService,
        NodeService $nodeService,
        FieldVisitorFactory $fieldVisitorFactory
    ) {
        $this->tasticService = $tasticService;
        $this->fieldVisitorFactory = $fieldVisitorFactory;
        $this->nodeService = $nodeService;
    }

    public function completeNodeData(Node $node, Context $context): void
    {
        $this->nodeService->completeCustomNodeData(
            $node,
            $this->fieldVisitorFactory->createNodeDataVisitor($context)
        );
    }

    public function completePageData(Page $page, Node $node, Context $context, object $tasticFieldData): void
    {
        $this->tasticService->getTasticsMappedByType();

        // Get all referenced and ancestor node ids and fetched them in a single query.
        // They will be cached now, preventing the nodes from being fetched from DB multiple times.
        $referencedNodeIds = $this->fetchReferencedNodeIds($page);
        $ancestorNodeIds = $this->fetchAncestorNodeIds($node);
        $this->nodeService->getByIds(array_merge($referencedNodeIds, $ancestorNodeIds));

        foreach ($page->regions as $region) {
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tasticInstance) {
                    $this->completeTasticData($tasticInstance, $context, $tasticFieldData);
                }
            }
        }
    }

    public function completePageFolderData(PageFolder $pageFolder, Node $node, Context $context) :void
    {
        $pageFolder->configuration['pathTranslations'] = $this->getPathTranslations($node, $context);
        $pageFolder->breadcrumbs = $this->getBreadcrumbs($node, $context);
    }

    private function completeTasticData(TasticInstance $tasticInstance, Context $context, object $tasticFieldData)
    {
        $tasticDefinition = $this->getTasticDefinition($tasticInstance->tasticType);
        if ($tasticDefinition === null) {
            return;
        }

        $baseConfigurationBackup = [
            'mobile' => $tasticInstance->configuration->mobile,
            'tablet' => $tasticInstance->configuration->tablet,
            'desktop' => $tasticInstance->configuration->desktop,
        ];

        $schema = ConfigurationSchema::fromSchemaAndConfiguration(
            $tasticDefinition->configurationSchema['schema'],
            (array)$tasticInstance->configuration
        );

        $tasticInstanceId = $tasticInstance->tasticId;

        $fieldVisitor = $this->fieldVisitorFactory->createTasticDataVisitor(
            $context,
            ($tasticFieldData->$tasticInstanceId ?? [])
        );

        $tasticInstance->configuration = new TasticInstance\Configuration(
            array_merge(
                $schema->getCompleteValues($fieldVisitor),
                $baseConfigurationBackup
            )
        );
    }

    private function getTasticDefinition(string $tasticType): ?TasticDefinition
    {
        // TODO: Cache
        $tasticMap = $this->tasticService->getTasticsMappedByType();
        if (!isset($tasticMap[$tasticType])) {
            return null;
        }
        return $tasticMap[$tasticType];
    }

    /**
     * Iterates through all tastics and get the referenced node ids
     * @param Page $page
     * @return array
     */
    private function fetchReferencedNodeIds(Page $page): array
    {
        $visitor = new NodeReferenceGetterVisitor();

        foreach ($page->regions as $region) {
            foreach ($region->elements as $element) {
                foreach ($element->tastics as $tasticInstance) {
                    $tasticDefinition = $this->getTasticDefinition($tasticInstance->tasticType);
                    if ($tasticDefinition === null) {
                        continue;
                    }

                    $schema = ConfigurationSchema::fromSchemaAndConfiguration(
                        $tasticDefinition->configurationSchema['schema'],
                        (array)$tasticInstance->configuration
                    );

                    $schema->getCompleteValues($visitor);
                }
            }
        }

        return $visitor->getReferencedNodeIds();
    }

    private function fetchAncestorNodeIds(Node $node): array
    {
        $ancestorIds = [];

        if (is_array($node->path)) {
            $ancestorIds = $node->path;
        }

        if (is_string($node->path)) {
            // Node.Path starts with '/' so we are removing first character
            $path = substr($node->path, 1);

            if ($path) {
                $ancestorIds = explode('/', $path);
            }
        }

        return $ancestorIds;
    }

    /**
     * @param Node $node
     * @param Context $context
     * @return PageFolderBreadcrumb[]
     */
    private function getBreadcrumbs(Node $node, Context $context): array
    {
        $ancestorNodes = [];
        $breadcrumbs = [];

        $ancestorIds = $this->fetchAncestorNodeIds($node);

        // We want to display the breadcrumbs in a reverse order. Given the path /nodeId01/nodeId02",
        // we'll return first the breadcrumb for "nodeId02" and after "nodeId01".
        foreach (array_reverse($ancestorIds) as $ancestorsId) {
            $ancestorNode = $this->nodeService->get($ancestorsId);
            $ancestorNodes[] = $ancestorNode;
        }

        foreach ($ancestorNodes as $ancestorNode) {
            $pageBreadcrumbElement = new PageFolderBreadcrumb();
            $pageBreadcrumbElement->pageFolderId = $ancestorNode->nodeId;
            $pageBreadcrumbElement->ancestorIdsMaterializedPath = $ancestorNode->path ?? null;
            $pageBreadcrumbElement->pathTranslations = $this->getPathTranslations($ancestorNode, $context);

            $breadcrumbs[] = $pageBreadcrumbElement;
        }

        return $breadcrumbs;
    }

    private function getPathTranslations(Node $node, Context $context): array
    {
        $pathTranslations = [];
        $defaultLocalePath = $node->configuration['path'] ?? null;

        foreach ($context->project->languages as $language) {
            $pathTranslations[$language] = $node->configuration['pathTranslations'][$language] ?? $defaultLocalePath;
        }

        return $pathTranslations;
    }
}
