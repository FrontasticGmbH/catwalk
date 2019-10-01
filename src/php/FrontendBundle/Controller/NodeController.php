<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;

class NodeController extends Controller
{
    public function viewAction(Request $request, Context $context, string $nodeId)
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);

        $node = $nodeService->get($nodeId);

        if (isset($node->configuration['separateTidewaysTransaction'])) {
            if ($node->configuration['separateTidewaysTransaction'] === true) {
                \Tideways\Profiler::setTransactionName('Node: ' . $request->getPathInfo());
            }
        }

        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataProvider->fetchDataFor($node, $context, $request->query->get('s', []), $page),
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
