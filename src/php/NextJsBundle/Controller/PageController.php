<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult;
use Frontastic\Catwalk\NextJsBundle\Domain\DynamicPageService;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\PageDataCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController
{
    private SiteBuilderPageService $siteBuilderPageService;
    private FromFrontasticReactMapper $mapper;
    private NodeService $nodeService;
    private PageService $pageService;
    private PageDataCompletionService $completionService;
    private ViewDataProvider $viewDataProvider;
    private DynamicPageService $dynamicPageService;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        DynamicPageService $dynamicPageService,
        FromFrontasticReactMapper $mapper,
        NodeService $nodeService,
        PageService $pageService,
        PageDataCompletionService $completionService,
        ViewDataProvider $viewDataProvider
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->dynamicPageService = $dynamicPageService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->completionService = $completionService;
        $this->mapper = $mapper;
        $this->viewDataProvider = $viewDataProvider;
    }

    public function indexAction(Request $request, Context $context)
    {
        if (!$request->query->has('path')) {
            throw new BadRequestHttpException('Missing path');
        }
        if (!$request->query->has('locale')) {
            throw new BadRequestHttpException('Missing locale');
        }

        $node = null;

        $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage(
            $request->query->get('path'),
            $request->query->get('locale')
        );

        if ($nodeId !== null) {
            $node = $this->nodeService->get($nodeId);
        }

        if ($node === null) {
            $dynamicPageResult = $this->dynamicPageService->handleDynamicPage($request, $context);
            if ($dynamicPageResult instanceof DynamicPageRedirectResult) {
                return $this->dynamicPageService->createRedirectResponse($dynamicPageResult);
            }
            if ($dynamicPageResult instanceof DynamicPageSuccessResult) {
                $node = $this->dynamicPageService->matchNodeFor($dynamicPageResult);
            }
        }

        if ($node === null) {
            throw new NotFoundHttpException('Could not resolve page from path');
        }

        $this->completionService->completeNodeData($node, $context);

        $page = $this->pageService->fetchForNode($node, $context);

        $pageViewData = $this->viewDataProvider->fetchDataFor($node, $context, [], $page);

        $this->completionService->completePageData($page, $node, $context, $pageViewData->tastic);

        return [
            'pageFolder' => $this->mapper->map($node),
            'page' => $this->mapper->map($page),
            // Stream parameters is deprecated
            'data' => $this->mapper->map($pageViewData),
        ];
    }
}
