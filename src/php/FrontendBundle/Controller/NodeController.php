<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class NodeController
{

    private NodeService $nodeService;
    private PageService $pageService;
    private ViewDataProvider $viewDataProvider;
    private TrackingService $trackingService;
    private LoggerInterface $logger;

    public function __construct(
        NodeService $nodeService,
        PageService $pageService,
        ViewDataProvider $viewDataProvider,
        TrackingService $trackingService,
        LoggerInterface $logger
    ) {
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->viewDataProvider = $viewDataProvider;
        $this->trackingService = $trackingService;
        $this->logger = $logger;
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

        $streamParameters = $request->query->get('s', []);

        if (!is_array($streamParameters)) {
            $streamParameters = [];
            $this->logger->warning(
                'Stream Parameters in {controller} were no array, falling back to an empty array',
                [
                    'controller', self::class
                ]
            );
        }

        return [
            'node' => $node,
            'page' => $page,
            'data' => $this->viewDataProvider->fetchDataFor($node, $context, $streamParameters, $page),
        ];
    }

    public function treeAction(Request $request): Node
    {
        $root = $request->get('root', null);
        $depth = $request->get('depth', 1);

        return $this->nodeService->getTree($root, $depth);
    }
}
