<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DynamicPageService
{
    const DYNAMIC_PAGE_HOOK = 'dynamic-page-handler';

    private RequestService $requestService;
    private HooksService $hooksService;
    private MasterService $masterService;
    private NodeService $nodeService;

    public function __construct(
        RequestService $requestService,
        HooksService $hooksService,
        MasterService $masterService,
        NodeService $nodeService
    ) {
        $this->requestService = $requestService;
        $this->hooksService = $hooksService;
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
    }

    public function handleDynamicPage(SymfonyRequest $request, Context $context): ?DynamicPageResult
    {
        if (!$this->hooksService->isHookRegistered(self::DYNAMIC_PAGE_HOOK)) {
            return null;
        }

        /** @var \stdClass */
        $dynamicPagePayload = $this->hooksService->call(self::DYNAMIC_PAGE_HOOK, [
            $this->requestService->createApiRequest($request),
            // TODO: Create context
        ]);

        if (isset($dynamicPagePayload->ok) && $dynamicPagePayload->ok === false) {
            throw new \RuntimeException($dynamicPagePayload->message ?? 'Unknown error when executing dynamic page');
        }

        if (isset($dynamicPagePayload->redirectLocation)) {
            return new DynamicPageRedirectResult((array) $dynamicPagePayload);
        }
        return new DynamicPageSuccessResult((array) $dynamicPagePayload);
    }

    public function createRedirectResponse(DynamicPageRedirectResult $redirectResult): SymfonyResponse
    {
        $response = new JsonResponse(null, $redirectResult->statusCode);
        $response->headers->set('Location', $redirectResult->redirectLocation);
        // FIXME: We ignore the status message, should we even allow to have it set?
        // TODO: Set headers from $redirectResult
        return $response;
    }

    public function matchNodeFor(DynamicPageSuccessResult $result): ?Node
    {
        $nodeId = $this->masterService->matchNodeIdForCustomTypes(
            $result->dynamicPageType,
            $result->pageMatchingPayload
        );

        $node = $this->nodeService->get($nodeId);

        foreach ($node->streams as $streamIndex => $stream) {
            if ($stream['streamId'] === '__master') {
                $node->streams[$streamIndex]['preloadedValue'] = $result->dataSourcePayload;
            }
        }

        return $node;
    }
}
