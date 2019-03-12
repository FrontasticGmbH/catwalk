<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    public function viewAction(Context $context, Request $request, $id): array
    {
        /** @var MasterService $pageMatcherService */
        $pageMatcherService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get($pageMatcherService->matchNodeId(new PageMatcherContext(['categoryId' => $id])));
        $node->streams = $pageMatcherService->completeDefaultQuery(
            $node->streams,
            'category',
            $id
        );

        return [
            'node' => $node,
            'page' => $page = $pageService->fetchForNode($node),
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
}
