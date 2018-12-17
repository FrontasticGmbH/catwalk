<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    const PRODUCT_STREAM_KEY = '__product';

    public function viewAction(Context $context, Request $request): array
    {
        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataService = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        /** @var ProductApi $productApi */
        $productApi = $this->get('frontastic.catwalk.product_api');
        /** @var ProductRouter $productRouter */
        $productRouter = $this->get(ProductRouter::class);

        $productQuery = $productRouter->parseQueryFrom($request);
        $productQuery->locale = $context->locale;

        $product = $productApi->getProduct($productQuery);

        $node = $nodeService->get(
            $masterService->matchNodeId(new PageMatcherContext(['productId' => $product->productId]))
        );
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            'product',
            $product->productId
        );

        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
