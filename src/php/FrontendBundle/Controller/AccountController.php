<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $masterService = $this->get(MasterService::class);
        $nodeService = $this->get(NodeService::class);
        $dataService = $this->get(ViewDataProvider::class);
        $pageService = $this->get(PageService::class);

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
