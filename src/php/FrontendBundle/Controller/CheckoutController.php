<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\KameleoonBundle\Domain\TrackingService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends Controller
{
    public function cartAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['cart' => true]));
    }

    public function checkoutAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        $this->get(TrackingService::class)->reachStartCheckout($context);
        return $this->getNode($context, new PageMatcherContext(['checkout' => true]));
    }

    public function finishedAction(Context $context, Request $request): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode(
            $context,
            new PageMatcherContext([
                'checkoutFinished' => true,
                'orderId' => $request->get('order', null)
            ]),
            'order'
        );
    }

    private function getNode(Context $context, PageMatcherContext $pageMatcherContext, ?string $pageType = null): array
    {
        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataService = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get(
            $masterService->matchNodeId($pageMatcherContext)
        );
        $node->nodeType = array_keys(array_filter((array) $pageMatcherContext))[0] ?? 'unknown';

        if ($pageMatcherContext->orderId !== null) {
            foreach ($node->streams as $streamKey => $streamConfig) {
                if ($streamConfig['streamId'] === '__master') {
                    $node->streams[$streamKey]['type'] = 'order';
                    $node->streams[$streamKey]['configuration'] = ['order' => $pageMatcherContext->orderId];
                }
            }
        }

        $page = $pageService->fetchForNode($node, $context);

        // Cart is available via tastify() in the front-end already
        if ($pageType !== null) {
            $masterService->completeTasticStreamConfigurationWithMasterDefault($page, $pageType);
        }

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
