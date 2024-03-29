<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController
{

    private MasterService $masterService;
    private NodeService $nodeService;
    private ViewDataProvider $viewDataProvider;
    private PageService $pageService;
    private ProductRouter $productRouter;
    private TrackingService $trackingService;
    private ProductApi $productApi;

    public function __construct(
        MasterService $masterService,
        NodeService $nodeService,
        ViewDataProvider $viewDataProvider,
        PageService $pageService,
        ProductRouter $productRouter,
        TrackingService $trackingService,
        ProductApi $productApi
    ) {
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->viewDataProvider = $viewDataProvider;
        $this->pageService = $pageService;
        $this->productRouter = $productRouter;
        $this->trackingService = $trackingService;
        $this->productApi = $productApi;
    }

    const PRODUCT_STREAM_KEY = '__product';

    public function viewAction(Context $context, Request $request)
    {
        // FIXME: Product is loaded to often in this request (1x identify, 1x generate URL, 1x stream), needs optimize!

        $productId = $this->productRouter->identifyFrom($request, $context);
        if (!$productId) {
            throw new NotFoundHttpException();
        }

        $product = $this->productApi->getProduct(
            ProductApi\Query\SingleProductQuery::byProductIdWithLocale($productId, $context->locale)
        );

        $currentUrl = parse_url($request->getRequestUri(), PHP_URL_PATH);
        $currentQuery = parse_url($request->getRequestUri(), PHP_URL_QUERY);

        $correctUrl = $this->productRouter->generateUrlFor($product);
        if ($currentUrl !== $correctUrl) {
            $redirectUrl = $correctUrl;
            // Keeping current query params intact with new URL
            if (trim($currentQuery) !== '') {
                $redirectUrl = $correctUrl . '?' . $currentQuery;
            }
            // Race condition: this redirect is not handled gracefully by the JS stack
            return new RedirectResponse($redirectUrl, 301);
        }

        if ($productId === null) {
            throw new NotFoundHttpException();
        }

        $node = $this->nodeService->get(
            $this->masterService->matchNodeId(new PageMatcherContext([
                'entity' => $product,
                'productId' => $productId,
            ]))
        );
        $node->nodeType = 'product';
        $node->streams = $this->masterService->completeDefaultQuery(
            $node->streams,
            'product',
            $productId
        );

        $page = $this->pageService->fetchForNode($node, $context);

        $this->trackingService->trackPageView($context, $node->nodeType);
        $this->trackingService->reachViewProduct($context, $product);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $this->viewDataProvider->fetchDataFor($node, $context, [], $page),
        ];
    }
}
