<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamContext;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext;
use GuzzleHttp\Promise\PromiseInterface;

class StreamHandlerToDataSourceHandlerAdapter implements StreamHandlerV2
{
    private string $extensionName;
    private ExtensionService $extensionService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $fromFrontasticReactMapper;
    private ContextCompletionService $contextCompletionService;

    public function __construct(
        ExtensionService $hooksService,
        RequestService $requestService,
        FromFrontasticReactMapper $fromFrontasticReactMapper,
        ContextCompletionService $contextCompletionService,
        string $extensionName
    ) {
        $this->extensionName = $extensionName;
        $this->extensionService = $hooksService;
        $this->requestService = $requestService;
        $this->fromFrontasticReactMapper = $fromFrontasticReactMapper;
        $this->contextCompletionService = $contextCompletionService;
    }

    public function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        $dataSourceContext = $this->createDataSourceContext($streamContext);
        $timeout = $dataSourceContext->frontasticContext->project->configuration["extensions"]["dataSourceTimeout"]
            ?? null;

        $extensionResult = $this->extensionService->callDataSource(
            $this->extensionName,
            [
                $this->fromFrontasticReactMapper->map($stream),
                $dataSourceContext
            ],
            $timeout
        );
        return $this->convertDataSourceResult($extensionResult);
    }

    public function convertDataSourceResult(PromiseInterface $extensionResult): PromiseInterface
    {
        return $extensionResult->then(
            function (?string $responseBody) {
                $obj = json_decode($responseBody);

                // If there is not dataSourcePayload field, the response is
                // likely an error. Pass it through unmodified.
                if (!$obj || !isset($obj->dataSourcePayload)) {
                    return $responseBody;
                }

                return $obj->dataSourcePayload;
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
