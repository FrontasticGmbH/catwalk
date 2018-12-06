<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NodeController extends Controller
{
    public function viewAction(Request $request, Context $context, string $nodeId): array
    {
        $nodeService = $this->get(NodeService::class);
        $dataProvider = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get($nodeId);

        $streamParameterMap = $this->extractStreamParameters($node, $request);

        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataProvider->fetchDataFor($node, $context, $streamParameterMap, $page),
        ];
    }

    private function extractStreamParameters(Node $node, Request $request)
    {
        if (!$request->query->has('s')) {
            return [];
        }
        $streamParameters = $request->query->get('s');

        // Expecting s[$streamId][...] query parameters that translate to e.g. ProductQuery
        return $streamParameters;
    }

    public function treeAction(Request $request): Node
    {
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $root = $request->get('root', null);
        $depth = $request->get('depth', 1);

        return $nodeService->getTree($root, $depth);
    }
}
