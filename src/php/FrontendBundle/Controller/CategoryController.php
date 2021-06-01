<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\CategoryRouter;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryController
{
    private MasterService $masterService;
    private NodeService $nodeService;
    private ViewDataProvider $viewDataProvider;
    private PageService $pageService;
    private CategoryRouter $categoryRouter;
    private TrackingService $trackingService;
    private ProductApi $productApi;

    public function __construct(
        MasterService $masterService,
        NodeService $nodeService,
        ViewDataProvider $viewDataProvider,
        PageService $pageService,
        CategoryRouter $categoryRouter,
        TrackingService $trackingService,
        ProductApi $productApi
    ) {
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->viewDataProvider = $viewDataProvider;
        $this->pageService = $pageService;
        $this->categoryRouter = $categoryRouter;
        $this->trackingService = $trackingService;
        $this->productApi = $productApi;
    }

    public function viewAction(Context $context, Request $request)
    {
        $id = $this->categoryRouter->identifyFrom($request, $context);
        if (!$id) {
            throw new NotFoundHttpException();
        }

        $category = $this->findCategoryById($id, $context);

        $currentUrl = parse_url($request->getRequestUri(), PHP_URL_PATH);
        $correctUrl = $this->categoryRouter->generateUrlFor($category);
        if ($currentUrl !== $correctUrl) {
            // Race condition: this redirect is not handled gracefully by the JS stack
            return new RedirectResponse($correctUrl, 301);
        }

        $node = $this->nodeService->get(
            $this->masterService->matchNodeId(new PageMatcherContext([
                'entity' => $category,
                'categoryId' => $id,
            ]))
        );
        $node->nodeType = 'category';
        $node->streams = $this->masterService->completeDefaultQuery(
            $node->streams,
            'category',
            $id
        );

        $this->trackingService->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page = $this->pageService->fetchForNode($node, $context),
            'data' => $this->viewDataProvider->fetchDataFor($node, $context, $request->query->get('s', []), $page),
        ];
    }

    public function allAction(Context $context)
    {
        // TODO: Allow fetching of more than 500 categories by paging
        return [
            'categories' => $this->productApi->getCategories(new CategoryQuery([
                'locale' => $context->locale,
                'limit' => 500,
            ])),
        ];
    }

    private function findCategoryById(string $categoryId, Context $context): ?Category
    {
        $categoryQuery = new CategoryQuery([
            'locale' => $context->locale,
            'limit' => 500,
            'parentId' => $categoryId,
        ]);
        foreach ($this->productApi->getCategories($categoryQuery) as $category) {
            if ($category->categoryId === $categoryId) {
                return $category;
            }
        }

        return null;
    }
}
