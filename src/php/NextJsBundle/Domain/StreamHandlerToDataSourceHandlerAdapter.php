<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamContext;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class StreamHandlerToDataSourceHandlerAdapter implements StreamHandlerV2
{
    private string $dataSourceIdentifier;
    private HooksService $hooksService;
    private FromFrontasticReactMapper $mapper;

    public function __construct(HooksService $hooksService, string $dataSourceIdentifier)
    {
        $this->dataSourceIdentifier = $dataSourceIdentifier;
        $this->hooksService = $hooksService;

        $this->mapper = new FromFrontasticReactMapper();
    }

    public function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        return Promise\promise_for(
            $this->hooksService->callExpectArray(
                $this->dataSourceIdentifier,
                [
                    $this->mapper->map($stream),
                    $this->mapper->map($streamContext),
                ]
            )
        );
    }
}
