<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
        $masterService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\MasterService');
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $dataService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');

        $node = $nodeService->get(
            $masterService->matchNodeId($pageMatcherContext)
        );
        $page = $pageService->fetchForNode($node);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
