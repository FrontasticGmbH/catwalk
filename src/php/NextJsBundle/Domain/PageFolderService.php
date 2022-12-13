<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderTreeNode;

class PageFolderService
{
    private SiteBuilderPageService $siteBuilderPageService;
    private NodeService $nodeService;
    private FromFrontasticReactMapper $mapper;
    private RouteService $routeService;
    private PageDataCompletionService $completionService;

    public function __construct(
        SiteBuilderPageService    $siteBuilderPageService,
        NodeService               $nodeService,
        FromFrontasticReactMapper $mapper,
        RouteService              $routeService,
        PageDataCompletionService $completionService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->mapper = $mapper;
        $this->routeService = $routeService;
        $this->completionService = $completionService;
    }

    /**
     * @param Context $context
     * @param string $locale
     * @param int $depth
     * @param string|null $path
     * @return PageFolderTreeNode[]
     */
    public function getTree(Context $context, string $locale, int $depth, string $path = null): array
    {
        $nodeId = null;
        if ($path) {
            $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $locale);
        }

        $nodes = $this->nodeService->getNodes($nodeId, $depth);
        $routes = $this->routeService->generateRoutes($nodes);

        $tree = [];
        foreach ($nodes as $node) {
            if (!$this->isValidNode($node, $routes)) {
                continue;
            }

            $pageFolderTreeNode = $this->mapNodeToPageFolderTreeNode($node, $context);
            $tree[] = $pageFolderTreeNode;
        }

        return $tree;
    }

    /**
     * Check if this is not a virtual node and if the node has routes
     */
    private function isValidNode(Node $node, array $routes): bool
    {
        if ($node->nodeId && $this->nodeHasRoutes($node, $routes)) {
            return true;
        }

        return false;
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
     * @param Context $context
     * @return PageFolderTreeNode
     */
    private function mapNodeToPageFolderTreeNode(Node $node, Context $context): PageFolderTreeNode
    {
        /** @var PageFolder $pageFolder */
        $pageFolder = $this->mapper->map($node);
        $this->completionService->completePageFolderData($pageFolder, $node, $context);

        $pageFolderTree = new PageFolderTreeNode();
        $pageFolderTree->pageFolderId = $pageFolder->pageFolderId;
        $pageFolderTree->pageFolderType = $pageFolder->pageFolderType;
        $pageFolderTree->configuration = $pageFolder->configuration;
        $pageFolderTree->name = $pageFolder->name;
        $pageFolderTree->ancestorIdsMaterializedPath = $pageFolder->ancestorIdsMaterializedPath;
        $pageFolderTree->depth = $pageFolder->depth;
        $pageFolderTree->sort = $pageFolder->sort;

        return $pageFolderTree;
    }
}
