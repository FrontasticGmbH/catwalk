<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderStructureValue;
use Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion\LocalizedValuePicker;

class PageFolderService
{
    private SiteBuilderPageService $siteBuilderPageService;
    private NodeService $nodeService;
    private FromFrontasticReactMapper $mapper;
    private RouteService $routeService;
    private PageDataCompletionService $completionService;
    private PageService $pageService;

    public function __construct(
        SiteBuilderPageService    $siteBuilderPageService,
        NodeService               $nodeService,
        FromFrontasticReactMapper $mapper,
        RouteService              $routeService,
        PageDataCompletionService $completionService,
        PageService               $pageService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->mapper = $mapper;
        $this->routeService = $routeService;
        $this->completionService = $completionService;
        $this->pageService = $pageService;
    }

    /**
     * @param Context $context
     * @param string $locale
     * @param int $depth
     * @param string|null $path
     * @return PageFolderStructureValue[]
     */
    public function getStructure(Context $context, string $locale, int $depth, string $path = null): array
    {
        $nodeId = null;
        if ($path) {
            $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $locale);
        }

        $nodes = $this->nodeService->getNodes($nodeId, $depth);
        $routes = $this->routeService->generateRoutes($nodes);

        $structure = [];
        foreach ($nodes as $node) {
            if (!$this->isValidNode($node, $routes, $context)) {
                continue;
            }

            $pageFolderStructureRecord = $this->mapNodeToPageFolderStructureRecord($node, $context);
            $structure[] = $pageFolderStructureRecord;
        }

        return $structure;
    }

    /**
     * Check if the node can be rendered by validating:
     * 1. The node is not virtual
     * 2. The node has routes
     * 3. The node has active pages
     */
    private function isValidNode(Node $node, array $routes, Context $context): bool
    {
        // Check if node is virtual
        if (!$node->nodeId) {
            return false;
        }

        // Cher if node has routes, maybe some parent nodes in the tree of this node has been deleted.
        if (!$this->nodeHasRoutes($node, $routes)) {
            return false;
        }

        // Check if node has active pages
        try {
            $this->pageService->fetchForNode($node, $context);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function nodeHasRoutes(Node $node, array $routes): bool
    {
        $nodeRoutes = array_filter(
            $routes,
            function (Route $route) use ($node) {
                return $route->nodeId === $node->nodeId;
            }
        );

        return !empty($nodeRoutes);
    }

    /**
     * @param Node $node
     * @param array $routes
     * @param Context $context
     * @return PageFolderStructureValue
     */
    private function mapNodeToPageFolderStructureRecord(Node $node, Context $context): PageFolderStructureValue
    {
        $urls = $this->siteBuilderPageService->getPathsForSiteBuilderPage($node->nodeId);

        /** @var PageFolder $pageFolder */
        $pageFolder = $this->mapper->map($node);
        $this->completionService->completePageFolderData($pageFolder, $node, $context);

        $pageFolderStructureRecord = new PageFolderStructureValue();
        $pageFolderStructureRecord->pageFolderId = $pageFolder->pageFolderId;
        $pageFolderStructureRecord->pageFolderType = $pageFolder->pageFolderType;
        $pageFolderStructureRecord->configuration = $pageFolder->configuration;
        $pageFolderStructureRecord->name = $pageFolder->name;
        $pageFolderStructureRecord->ancestorIdsMaterializedPath = $pageFolder->ancestorIdsMaterializedPath;
        $pageFolderStructureRecord->depth = $pageFolder->depth;
        $pageFolderStructureRecord->sort = $pageFolder->sort;
        $pageFolderStructureRecord->breadcrumbs = $pageFolder->breadcrumbs;
        $pageFolderStructureRecord->_urls = $urls;
        $pageFolderStructureRecord->_url = LocalizedValuePicker::getValueForCurrentLocale($context, $urls);

        return $pageFolderStructureRecord;
    }
}
