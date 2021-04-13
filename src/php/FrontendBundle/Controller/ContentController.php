<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler\Content;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ContentRouter;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentController
{
    const PRODUCT_STREAM_KEY = '__content';
    private MasterService $masterService;
    private NodeService $nodeService;
    private ViewDataProvider $viewDataProvider;
    private PageService $pageService;
    private Content $contentApi;
    private ContentRouter $contentRouter;
    private TrackingService $trackingService;

    public function __construct(
        MasterService $masterService,
        NodeService $nodeService,
        ViewDataProvider $viewDataProvider,
        PageService $pageService,
        Content $contentApi,
        ContentRouter $contentRouter,
        TrackingService $trackingService
    ) {
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->viewDataProvider = $viewDataProvider;
        $this->pageService = $pageService;
        $this->contentApi = $contentApi;
        $this->contentRouter = $contentRouter;
        $this->trackingService = $trackingService;
    }

    public function viewAction(Context $context, Request $request)
    {
        $contentId = $this->contentRouter->identifyFrom($request, $context);
        if (!$contentId) {
            throw new NotFoundHttpException();
        }

        $content = $this->contentApi->getContent($contentId, $context->locale);

        $currentUrl = parse_url($request->getRequestUri(), PHP_URL_PATH);
        if ($currentUrl !== ($correctUrl = $this->contentRouter->generateUrlFor($content))) {
            // Race condition: this redirect is not handled gracefully by the JS stack
            return new RedirectResponse($correctUrl, 301);
        }

        if ($contentId === null) {
            throw new NotFoundHttpException();
        }

        $node = $this->nodeService->get(
            $this->masterService->matchNodeId(new PageMatcherContext([
                'entity' => $content,
                'contentId' => $contentId,
            ]))
        );
        $node->nodeType = 'content';
        $node->streams = $this->masterService->completeDefaultQuery(
            $node->streams,
            'content',
            $contentId
        );

        $page = $this->pageService->fetchForNode($node, $context);

        $this->trackingService->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $this->viewDataProvider->fetchDataFor($node, $context, [], $page),
        ];
    }
}
