<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function viewAction(Context $context, $id): array
    {
        $pageMatcherService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataProvider = $this->get(ViewDataProvider::class);
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
            'data' => $dataProvider->fetchDataFor($node, $context, [], $page),
        ];
    }

    public function allAction(Context $context)
    {
        $productApi = $this->get('frontastic.catwalk.product_api');

        // TODO: Allow fetching of more than 500 categories by paging
        return [
            'categories' => $productApi->getCategories(new CategoryQuery([
                'locale' => $context->locale,
                'limit' => 500,
            ])),
        ];
    }
}
