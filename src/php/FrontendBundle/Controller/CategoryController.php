<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\CategoryRouter;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function viewAction(Context $context, Request $request): array
    {
        /** @var MasterService $pageMatcherService */
        $pageMatcherService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);
        /** @var CategoryRouter $categoryRouter */
        $categoryRouter = $this->get(CategoryRouter::class);

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

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page = $pageService->fetchForNode($node, $context),
            'data' => $dataProvider->fetchDataFor($node, $context, $request->query->get('s', []), $page),
        ];
    }

    public function allAction(Context $context)
    {
        /** @var ProductApi $productApi */
        $productApi = $this->get(ProductApi::class);

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
        $productApi = $this->get('frontastic.catwalk.product_api');

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
