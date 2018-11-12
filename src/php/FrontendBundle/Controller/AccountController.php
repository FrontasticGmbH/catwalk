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
        return $this->getNode($context, new PageMatcherContext(['account' => $context->session]));
    }

    public function forgotPasswordAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountForgotPassword' => $context->session]));
    }

    public function confirmAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountConfirm' => $context->session]));
    }

    public function profileAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountProfile' => $context->session]));
    }

    public function addressesAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountAddresses' => $context->session]));
    }

    public function ordersAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountOrders' => $context->session]));
    }

    public function wishlistsAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountWishlists' => $context->session]));
    }

    public function vouchersAction(Context $context): array
    {
        return $this->getNode($context, new PageMatcherContext(['accountVouchers' => $context->session]));
    }

    private function getNode(Context $context, PageMatcherContext $pageMatcherContext): array
    {
        $masterService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\MasterService');
        $nodeService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $dataService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider');
        $pageService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\PageService');

        $queryData = array_filter((array) $pageMatcherContext);
        $node = $nodeService->get($masterService->matchNodeId($pageMatcherContext));
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            key($queryData),
            $context->session->account->accountId ?? null
        );

        return [
            'node' => $node,
            'page' => $page = $pageService->fetchForNode($node),
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
