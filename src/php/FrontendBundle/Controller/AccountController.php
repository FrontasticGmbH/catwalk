<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
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
        /** @var MasterService */
        $masterService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataService */
        $dataService = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);

        $queryData = array_filter((array) $pageMatcherContext);
        $node = $nodeService->get($masterService->matchNodeId($pageMatcherContext));
        $node->nodeType = array_keys(array_filter((array) $pageMatcherContext))[0] ?? 'unknown';
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            key($queryData),
            $context->session->account->accountId ?? null
        );

        $page = $pageService->fetchForNode($node, $context);

        $masterService->completeTasticStreamConfigurationWithMasterDefault($page, key($queryData));

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
