<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ContentRouter;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentController extends Controller
{
    const PRODUCT_STREAM_KEY = '__content';

    public function viewAction(Context $context, Request $request)
    {
        /** @var MasterService $masterService */
        $masterService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataService */
        $dataService = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);

        /** @var ContentApi $contentApi */
        $contentApi = $this->get('frontastic.catwalk.content_api');
        /** @var ContentRouter $contentRouter */
        $contentRouter = $this->get(ContentRouter::class);

        $contentId = $contentRouter->identifyFrom($request, $context);
        if (!$contentId) {
            throw new NotFoundHttpException();
        }

        $content = $contentApi->getContent($contentId, $context->locale);

        $currentUrl = parse_url($request->getRequestUri(), PHP_URL_PATH);
        if ($currentUrl !== ($correctUrl = $contentRouter->generateUrlFor($content))) {
            // Race condition: this redirect is not handled gracefully by the JS stack
            return new RedirectResponse($correctUrl, 301);
        }

        if ($contentId === null) {
            throw new NotFoundHttpException();
        }

        $node = $nodeService->get(
            $masterService->matchNodeId(new PageMatcherContext([
                'entity' => $content,
                'contentId' => $contentId,
            ]))
        );
        $node->streams = $masterService->completeDefaultQuery(
            $node->streams,
            'content',
            $contentId
        );

        $page = $pageService->fetchForNode($node, $context);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataService->fetchDataFor($node, $context, [], $page),
        ];
    }
}
