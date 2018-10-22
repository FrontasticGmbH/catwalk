<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ProductController extends Controller
{
    const PRODUCT_STREAM_KEY = '__product';

    public function viewAction(Context $context, $id): array
    {
        $masterService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\MasterService');
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $dataService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');

        $node = $nodeService->get(
            $masterService->matchNodeId(new PageMatcherContext(['productId' => $id]))
        );
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            'product',
            $id
        );

        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
