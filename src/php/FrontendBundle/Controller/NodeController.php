<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Component\HttpFoundation\Request;

class NodeController
{

    private NodeService $nodeService;
    private PageService $pageService;
    private ViewDataProvider $viewDataProvider;
    private TrackingService $trackingService;

    public function __construct(
        NodeService $nodeService,
        PageService $pageService,
        ViewDataProvider $viewDataProvider,
        TrackingService $trackingService
    ) {
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->viewDataProvider = $viewDataProvider;
        $this->trackingService = $trackingService;
    }

    public function viewAction(Request $request, Context $context, string $nodeId)
    {
        $node = $this->nodeService->get($nodeId);

        if (class_exists('Tideways\Profiler') &&
            ($node->configuration['separateTidewaysTransaction'] ?? false) === true) {
            \Tideways\Profiler::setTransactionName('Node: ' . $node->nodeId);
        }

        $page = $this->pageService->fetchForNode($node, $context);

        $this->trackingService->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $this->viewDataProvider->fetchDataFor($node, $context, $request->query->get('s', []), $page),
        ];
    }

    public function treeAction(Request $request): Node
    {
        $root = $request->get('root', null);
        $depth = $request->get('depth', 1);

        return $this->nodeService->getTree($root, $depth);
    }
}
