<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ReplicatorBundle\Domain\Result;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;

class NodeController extends Controller
{
    public function viewAction(Request $request, Context $context, string $nodeId): array
    {
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');
        $dataProvider = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');

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
