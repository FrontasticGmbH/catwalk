<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

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


    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        NodeService $nodeService,
        FromFrontasticReactMapper $mapper,
        RouteService $routeService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->mapper = $mapper;
        $this->routeService = $routeService;
    }

    /**
     * @param string $locale
     * @param int $depth
     * @param string|null $path
     * @return PageFolderTreeNode[]
     */
    public function getTree(string $locale, int $depth, string $path = null): array
    {
        $nodeId = null;
        if ($path) {
            $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $locale);
        }

        $nodes = $this->nodeService->getNodes($nodeId, $depth);
        $routes = $this->routeService->generateRoutes($nodes);

        $pageFolderTreeNodeIndex = [];
        foreach ($nodes as $node) {
            if (!$node->nodeId) {
                // Skip virtual root node
                continue;
            }

            if (!$this->nodeHasRoutes($node, $routes)) {
                // There is no route for this node, maybe some parent nodes in the tree of this node has been deleted.
                continue;
            }

            $pageFolderTreeNode = $this->mapNodeToPageFolderTreeNode($node);
            $pageFolderTreeNodeIndex[$node->nodeId] = $pageFolderTreeNode;
        }

        return array_values($pageFolderTreeNodeIndex);
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
     * @return PageFolderTreeNode
     */
    private function mapNodeToPageFolderTreeNode(Node $node): PageFolderTreeNode
    {
        /** @var PageFolder $pageFolder */
        $pageFolder = $this->mapper->map($node);

        $pageFolderTree = new PageFolderTreeNode();
        $pageFolderTree->pageFolderId = $pageFolder->pageFolderId;
        $pageFolderTree->pageFolderType = $pageFolder->pageFolderType;
        $pageFolderTree->name = $pageFolder->name;
        $pageFolderTree->ancestorIdsMaterializedPath = $pageFolder->ancestorIdsMaterializedPath;
        $pageFolderTree->depth = $pageFolder->depth;
        $pageFolderTree->sort = $pageFolder->sort;

        return $pageFolderTree;
    }
}
