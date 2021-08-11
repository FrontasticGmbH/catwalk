<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
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
    private ViewDataProvider $viewDataProvider;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        FromFrontasticReactMapper $mapper,
        NodeService $nodeService,
        PageService $pageService,
        ViewDataProvider $viewDataProvider
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
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

        $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage(
            $request->query->get('path'),
            $request->query->get('locale')
        );

        if ($nodeId === null) {
            throw new NotFoundHttpException();
        }

        $node = $this->nodeService->get($nodeId);
        $page = $this->pageService->fetchForNode($node, $context);

        return [
            'pageFolder' => $this->mapper->map($node),
            'page' => $this->mapper->map($page),
            // Stream parameters is deprecated
            'data' => $this->mapper->map($this->viewDataProvider->fetchDataFor($node, $context, [], $page)),
        ];
    }
}
