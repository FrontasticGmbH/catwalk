<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController
{
    private TrackingService $trackingService;
    private MasterService $masterService;
    private NodeService $nodeService;
    private ViewDataProvider $viewDataProvider;
    private PageService $pageService;

    public function __construct(
        TrackingService $trackingService,
        MasterService $masterService,
        NodeService $nodeService,
        ViewDataProvider $viewDataProvider,
        PageService $pageService
    ) {
        $this->trackingService = $trackingService;
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->viewDataProvider = $viewDataProvider;
        $this->pageService = $pageService;
    }

    public function cartAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['cart' => true]));
    }

    public function checkoutAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        $this->trackingService->reachStartCheckout($context);
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
        $masterService = $this->masterService;
        $nodeService = $this->nodeService;
        $dataService = $this->viewDataProvider;
        $pageService = $this->pageService;

        $node = $nodeService->get(
            $masterService->matchNodeId($pageMatcherContext)
        );
        $node->nodeType = array_keys(array_filter((array)$pageMatcherContext))[0] ?? 'unknown';

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

        $this->trackingService->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
