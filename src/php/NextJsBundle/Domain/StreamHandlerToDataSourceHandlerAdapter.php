<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamContext;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext;
use Frontastic\Common\HttpClient\Response;
use GuzzleHttp\Promise\PromiseInterface;

class StreamHandlerToDataSourceHandlerAdapter implements StreamHandlerV2
{
    private string $hookName;
    private HooksService $hooksService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $fromFrontasticReactMapper;
    private ContextCompletionService $contextCompletionService;

    public function __construct(
        HooksService $hooksService,
        RequestService $requestService,
        FromFrontasticReactMapper $fromFrontasticReactMapper,
        ContextCompletionService $contextCompletionService,
        string $hookName
    ) {
        $this->hookName = $hookName;
        $this->hooksService = $hooksService;
        $this->requestService = $requestService;
        $this->fromFrontasticReactMapper = $fromFrontasticReactMapper;
        $this->contextCompletionService = $contextCompletionService;
    }

    public function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        return $this->hooksService->callAsync(
            $this->hookName,
            [
                $this->fromFrontasticReactMapper->map($stream),
                $this->createDataSourceContext($streamContext)
            ],
            function (?Response $response) {
                if (!isset($response->dataSourcePayload)) {
                    throw new \RuntimeException(
                        "Invalid data-source response: Missing `dataSourcePayload`: " .
                        json_encode($response)
                    );
                }

                return $response->dataSourcePayload;
            }
        );
    }

    private function createDataSourceContext(StreamContext $streamContext): DataSourceContext
    {
        /** @var Context $context */
        $context = $this->fromFrontasticReactMapper->map($streamContext->context);
        $context = $this->contextCompletionService->completeContextData($context, $streamContext->context);

        return new DataSourceContext([
            'frontasticContext' => $context,
            'pageFolder' => $this->fromFrontasticReactMapper->map($streamContext->node),
            'page' => $this->fromFrontasticReactMapper->map($streamContext->page),
            'usingTastics' => $this->fromFrontasticReactMapper->mapAny($streamContext->usingTastics),
            'request' => (
            $streamContext->request ?
                $this->requestService->createApiRequest($streamContext->request) :
                null
            ),
        ]);
    }
}
