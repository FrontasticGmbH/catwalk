<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;

class NodeController extends Controller
{
    public function viewAction(Request $request, Context $context, string $nodeId, LoggerInterface $logger)
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);

        $node = $nodeService->get($nodeId);

        if (class_exists('Tideways\Profiler') &&
            ($node->configuration['separateTidewaysTransaction'] ?? false) === true) {
            \Tideways\Profiler::setTransactionName('Node: ' . $node->nodeId);
        }

        $page = $pageService->fetchForNode($node, $context);

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);

        $streamParameters = $request->query->get('s', []);

        if (!is_array($streamParameters)) {
            $streamParameters = [];
            $logger->warning(
                'Stream Parameters in {controller} were no array, falling back to an empty array',
                [
                    'controller', self::class
                ]
            );
        }

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataProvider->fetchDataFor($node, $context, $streamParameters, $page),
        ];
    }

    public function treeAction(Request $request): Node
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        $root = $request->get('root', null);
        $depth = $request->get('depth', 1);

        return $nodeService->getTree($root, $depth);
    }
}
