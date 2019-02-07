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

class SearchController extends Controller
{
    public function searchAction(Context $context, string $phrase): array
    {
        /** @var MasterService $pageMatcherService */
        $pageMatcherService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get($pageMatcherService->matchNodeId(new PageMatcherContext(['search' => $phrase])));
        $node->streams = $pageMatcherService->completeDefaultQuery($node->streams, 'query', $phrase);

        return [
            'node' => $node,
            'page' => $page = $pageService->fetchForNode($node),
            'data' => $dataProvider->fetchDataFor($node, $context, [], $page),
        ];
    }
}
