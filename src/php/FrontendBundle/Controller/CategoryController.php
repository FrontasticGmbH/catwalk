<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class CategoryController extends Controller
{
    public function viewAction(Context $context, $id): array
    {
        $pageMatcherService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\MasterService');
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $dataProvider = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');

        $nodeId = $pageMatcherService->matchNodeId(
            new PageMatcherContext(['categoryId' => $id])
        );

        $node = $nodeService->get($nodeId);

        $page = $pageService->fetchForNode($node);

        $node->streams = $pageMatcherService->completeDefaultQuery(
            $node->streams,
            'category',
            $id
        );

        return [
            'page' => $page,
            'node' => $node,
            'data' => $dataProvider->fetchDataFor($node, $context, [], $page),
        ];
    }

    public function allAction(Context $context)
    {
        $productApi = $this->get('frontastic.catwalk.product_api');

        return [
            'categories' => $productApi->getCategories(new CategoryQuery([
                'locale' => $context->locale,
            ])),
        ];
    }
}
