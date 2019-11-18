<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CheckoutController extends Controller
{
    public function cartAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['cart' => true]));
    }

    public function checkoutAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['checkout' => true]));
    }

    public function finishedAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['checkoutFinished' => true]));
    }

    private function getNode(Context $context, PageMatcherContext $pageMatcherContext): array
    {
        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataService = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

        $node = $nodeService->get(
            $masterService->matchNodeId($pageMatcherContext)
        );
        $page = $pageService->fetchForNode($node, $context);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
