<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    const PRODUCT_STREAM_KEY = '__product';

    public function viewAction(Context $context, $id): array
    {
        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataService = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get(
            $masterService->matchNodeId(new PageMatcherContext(['productId' => $id]))
        );
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            'product',
            $id
        );

        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
