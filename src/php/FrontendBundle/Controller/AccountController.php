<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class AccountController extends Controller
{
    public function indexAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['account' => true]));
    }

    public function forgotPasswordAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountForgotPassword' => true]));
    }

    public function confirmAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountConfirm' => true]));
    }

    public function profileAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountProfile' => true]));
    }

    public function addressesAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountAddresses' => true]));
    }

    public function ordersAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountOrders' => true]));
    }

    public function wishlistsAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountWishlists' => true]));
    }

    public function vouchersAction(Context $context): array
    {
        // @TODO: Add information about cart here, so that we can build selectors on top if this?
        return $this->getNode($context, new PageMatcherContext(['accountVouchers' => true]));
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
