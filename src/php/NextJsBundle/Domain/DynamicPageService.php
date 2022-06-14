<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DynamicPageService
{
    private RequestService $requestService;
    private ExtensionService $extensionService;
    private MasterService $masterService;
    private NodeService $nodeService;
    private FromFrontasticReactMapper $mapper;

    public function __construct(
        RequestService $requestService,
        ExtensionService $hooksService,
        MasterService $masterService,
        NodeService $nodeService,
        FromFrontasticReactMapper $mapper
    ) {
        $this->requestService = $requestService;
        $this->extensionService = $hooksService;
        $this->masterService = $masterService;
        $this->nodeService = $nodeService;
        $this->mapper = $mapper;
    }

    public function handleDynamicPage(SymfonyRequest $request, Context $context): ?DynamicPageResult
    {
        if (!$this->extensionService->hasDynamicPageHandler()) {
            return null;
        }

        $dynamicPageContext = $this->createDynamicPageContext($context);
        $timeout = $dynamicPageContext->frontasticContext->project->configuration["extensions"]["pageTimeout"] ?? null;

        /** @var \stdClass */
        $dynamicPagePayload = $this->extensionService->callDynamicPageHandler([
            $this->requestService->createApiRequest($request),
            $dynamicPageContext
        ], $timeout);

        if ($dynamicPagePayload === null) {
            return null;
        }

        if (isset($dynamicPagePayload->ok) && $dynamicPagePayload->ok === false) {
            throw new \RuntimeException($dynamicPagePayload->message ?? 'Unknown error when executing dynamic page');
        }

        if (isset($dynamicPagePayload->redirectLocation)) {
            return new DynamicPageRedirectResult((array)$dynamicPagePayload);
        }
        return new DynamicPageSuccessResult((array)$dynamicPagePayload);
    }

    /**
     * @deprecated Use RedirectService@createResponseFromDynamicPageRedirectResult instead
     */
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
            isset($result->pageMatchingPayload)
                ? $result->pageMatchingPayload
                : $result->dataSourcePayload
        );

        $node = $this->nodeService->get($nodeId);

        foreach ($node->streams as $streamIndex => $stream) {
            if ($stream['streamId'] === '__master') {
                $node->streams[$streamIndex]['preloadedValue'] = $result->dataSourcePayload;
            }
        }

        return $node;
    }

    private function createDynamicPageContext(Context $context): DynamicPageContext
    {
        return new DynamicPageContext([
            'frontasticContext' => $this->mapper->map($context)
        ]);
    }
}
