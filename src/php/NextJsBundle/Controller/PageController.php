<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\PreviewService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewData;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\PageDataResponse;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\PagePreviewContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\PagePreviewDataResponse;
use Frontastic\Catwalk\NextJsBundle\Domain\DynamicPageService;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\PageDataCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\RedirectService;
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
    private PreviewService $previewService;
    private PageDataCompletionService $completionService;
    private ViewDataProvider $viewDataProvider;
    private DynamicPageService $dynamicPageService;
    private RedirectService $redirectService;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        DynamicPageService $dynamicPageService,
        FromFrontasticReactMapper $mapper,
        NodeService $nodeService,
        PageService $pageService,
        PreviewService $previewService,
        PageDataCompletionService $completionService,
        ViewDataProvider $viewDataProvider,
        RedirectService $redirectService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->dynamicPageService = $dynamicPageService;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->previewService = $previewService;
        $this->completionService = $completionService;
        $this->mapper = $mapper;
        $this->viewDataProvider = $viewDataProvider;
        $this->redirectService = $redirectService;
    }

    public function indexAction(Request $request, Context $context)
    {
        $path = $this->getPath($request);
        $locale = $this->getLocale($request);

        $this->assertLocaleSupported($locale, $context);

        $node = null;

        $nodeId = $this->siteBuilderPageService->matchSiteBuilderPage($path, $locale);

        if ($nodeId !== null) {
            $node = $this->nodeService->get($nodeId);
        }

        if ($node === null) {
            $dynamicPageResult = $this->dynamicPageService->handleDynamicPage($request, $context);
            if ($dynamicPageResult instanceof DynamicPageRedirectResult) {
                return $this->redirectService->createResponseFromDynamicPageRedirectResult($dynamicPageResult);
            }
            if ($dynamicPageResult instanceof DynamicPageSuccessResult) {
                $node = $this->dynamicPageService->matchNodeFor($dynamicPageResult);
            }
        }

        if ($node === null) {
            $queryParams = $request->query->all();
            $redirectResponse = $this->redirectService->getRedirectResponseForPath($path, $queryParams, $context);
            if ($redirectResponse !== null) {
                return $redirectResponse;
            }

            throw new NotFoundHttpException('Could not resolve page from path');
        }

        $this->completionService->completeNodeData($node, $context);

        $page = $this->pageService->fetchForNode($node, $context);

        $page = $this->pageService->filterOutHiddenData($page);

        $pageViewData = $this->convertStreamErrors(
            $this->viewDataProvider->fetchDataFor($node, $context, [], $page)
        );

        $this->completionService->completePageData($page, $node, $context, $pageViewData->tastic);

        return new PageDataResponse([
            'pageFolder' => $this->mapper->map($node),
            'page' => $this->mapper->map($page),
            // Stream parameters is deprecated
            'data' => $this->mapper->map($pageViewData),
        ]);
    }

    public function previewAction(Request $request, Context $context)
    {
        if (!$request->query->has('previewId')) {
            throw new BadRequestHttpException('Missing previewId');
        }
        if (!$request->query->has('locale')) {
            throw new BadRequestHttpException('Missing locale');
        }

        $this->assertLocaleSupported($request->query->has('locale'), $context);

        $preview = $this->previewService->get($request->query->get('previewId'));

        $pageViewData = new \stdClass();
        if ($preview->node) {
            $pageViewData = $this->viewDataProvider->fetchDataFor($preview->node, $context, [], $preview->page);
        }

        $this->completionService->completePageData($preview->page, $preview->node, $context, $pageViewData->tastic);

        return new PagePreviewDataResponse([
            'previewId' => $request->query->get('previewId'),
            'pageFolder' => $this->mapper->map($preview->node),
            'page' => $this->mapper->map($preview->page),
            // Stream parameters is deprecated
            'data' => $this->mapper->map($pageViewData),
            'previewContext' => new PagePreviewContext([
                'customerName' => $context->customer->name
            ])
        ]);
    }

    /**
     * Replaces the word 'stream' with 'data source' in errors found in the stream property of a ViewData object.
     * @param ViewData $viewData
     * @return ViewData
     */
    private function convertStreamErrors(ViewData $viewData): ViewData
    {
        if (!property_exists($viewData, 'stream') || !is_iterable($viewData->stream)) {
            return $viewData;
        }

        foreach ($viewData->stream as &$streamData) {
            if (array_key_exists('ok', $streamData) && $streamData['ok'] === false && isset($streamData['message'])) {
                // If you are looking for the source of these errors they are in: StreamService@handle
                $streamData['message'] = str_replace(
                    ['No stream handler for stream', 'The stream has no type'],
                    ['No data source handler for data source', 'The data source has no type'],
                    strval($streamData['message'])
                );
            }
        }

        return $viewData;
    }

    /**
     * @param $locale
     * @param Context $context
     * @return void
     */
    private function assertLocaleSupported($locale, Context $context): void
    {
        if (!in_array($locale, $context->project->languages)) {
            throw new BadRequestHttpException(
                "Locale ${locale} not supported by project (" . implode(', ', $context->project->languages) . ")"
            );
        }
    }

    private function getPath(Request $request)
    {
        if (!$request->headers->has('Frontastic-Path') && !$request->query->has('path')) {
            throw new BadRequestHttpException('Missing path query parameter');
        }

        return $request->headers->get('Frontastic-Path') ?? $request->query->get('path');
    }

    private function getLocale($request)
    {
        if (!$request->headers->has('Frontastic-Locale') && !$request->query->has('locale')) {
            throw new BadRequestHttpException('Missing locale query parameter');
        }

        return $request->headers->get('Frontastic-Locale') ?? $request->query->get('locale');
    }
}
