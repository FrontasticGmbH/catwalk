<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamContext;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class StreamHandlerToDataSourceHandlerAdapter implements StreamHandlerV2
{
    private string $hookName;
    private HooksService $hooksService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $mapper;

    public function __construct(HooksService $hooksService, RequestService $requestService, string $hookName)
    {
        $this->hookName = $hookName;
        $this->hooksService = $hooksService;

        $this->mapper = new FromFrontasticReactMapper();
        $this->requestService = $requestService;
    }

    public function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        $hookServiceResponse = $this->hooksService->call(
            $this->hookName,
            [
                $this->mapper->map($stream),
                $this->createDataSourceContext($streamContext)
            ]
        );
        if (!isset($hookServiceResponse->dataSourcePayload)) {
            throw new \RuntimeException("Invalid data-source rersponse: Missing `dataSourcePayload`");
        }
        return Promise\promise_for(
            $hookServiceResponse->dataSourcePayload
        );
    }

    private function createDataSourceContext(StreamContext $streamContext): DataSourceContext
    {
        return new DataSourceContext([
            'frontasticContext' => $this->mapper->map($streamContext->context),
            'pageFolder' => $this->mapper->map($streamContext->node),
            'page' => $this->mapper->map($streamContext->page),
            'usingTastics' => $this->mapper->mapAny($streamContext->usingTastics),
            'request' => (
            $streamContext->request ?
                $this->requestService->createApiRequest($streamContext->request) :
                null
            ),
        ]);
    }
}
