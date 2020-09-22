<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter;
use Frontastic\Catwalk\KameleoonBundle\Domain\TrackingService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    const PRODUCT_STREAM_KEY = '__product';

    public function viewAction(Context $context, Request $request)
    {
        /** @var MasterService $masterService */
        $masterService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataService */
        $dataService = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);

        /** @var ProductApi $productApi */
        $productApi = $this->get('frontastic.catwalk.product_api');
        /** @var ProductRouter $productRouter */
        $productRouter = $this->get(ProductRouter::class);

        // FIXME: Product is loaded to often in this request (1x identify, 1x generate URL, 1x stream), needs optimize!

        $productId = $productRouter->identifyFrom($request, $context);
        if (!$productId) {
            throw new NotFoundHttpException();
        }

        $product = $productApi->getProduct(
            ProductApi\Query\SingleProductQuery::byProductIdWithLocale($productId, $context->locale)
        );

        $currentUrl = parse_url($request->getRequestUri(), PHP_URL_PATH);
        if ($currentUrl !== ($correctUrl = $productRouter->generateUrlFor($product))) {
            // Race condition: this redirect is not handled gracefully by the JS stack
            return new RedirectResponse($correctUrl, 301);
        }

        if ($productId === null) {
            throw new NotFoundHttpException();
        }

        $node = $nodeService->get(
            $masterService->matchNodeId(new PageMatcherContext([
                'entity' => $product,
                'productId' => $productId,
            ]))
        );
        $node->nodeType = 'product';
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            'product',
            $productId
        );

        $page = $pageService->fetchForNode($node, $context);

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);
        $this->get(TrackingService::class)->reachViewProduct($context, $product);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
