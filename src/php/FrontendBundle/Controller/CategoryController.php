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
use Symfony\Component\HttpFoundation\Request;

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

    public function viewAction(Context $context, Request $request): array
    {
        $pageMatcherService = $this->masterService;
        $nodeService = $this->nodeService;
        $dataProvider = $this->viewDataProvider;
        $pageService = $this->pageService;
        $categoryRouter = $this->categoryRouter;

        $id = $categoryRouter->identifyFrom($request, $context);

        $category = $this->findCategoryById($id, $context);
        $node = $nodeService->get(
            $pageMatcherService->matchNodeId(new PageMatcherContext([
                'entity' => $category,
                'categoryId' => $id,
            ]))
        );
        $node->nodeType = 'category';
        $node->streams = $pageMatcherService->completeDefaultQuery(
            $node->streams,
            'category',
            $id
        );

        $this->trackingService->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page = $pageService->fetchForNode($node, $context),
            'data' => $dataProvider->fetchDataFor($node, $context, $request->query->get('s', []), $page),
        ];
    }

    public function allAction(Context $context)
    {
        /** @var ProductApi $productApi */
        $productApi = $this->productApi;

        // TODO: Allow fetching of more than 500 categories by paging
        return [
            'categories' => $productApi->getCategories(new CategoryQuery([
                'locale' => $context->locale,
                'limit' => 500,
            ])),
        ];
    }

    private function findCategoryById(string $categoryId, Context $context): ?Category
    {
        $productApi = $this->productApi;

        $categoryQuery = new CategoryQuery([
            'locale' => $context->locale,
            'limit' => 500,
            'parentId' => $categoryId,
        ]);
        foreach ($productApi->getCategories($categoryQuery) as $category) {
            if ($category->categoryId === $categoryId) {
                return $category;
            }
        }

        return null;
    }
}
