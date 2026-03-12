<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use GuzzleHttp\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\Request;

class StreamHandlerV2Adapter implements StreamHandlerV2
{
    private StreamHandler $innerHandler;

    public function __construct(StreamHandler $innerHandler)
    {
        $this->innerHandler = $innerHandler;
    }

    /**
     * @inheritDoc
     */
    public function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        return $this->innerHandler->handleAsync(
            $stream,
            $streamContext->context,
            array_merge(
                $streamContext->parameters,
                isset($streamContext->request)
                    ? $this->extractParametersFor($streamContext->request, $stream)
                    : []
            )
        );
    }

    private function extractParametersFor(Request|null $request, Stream $stream): array
    {
        if ($request === null || $request->request === null || !$request->request->has('s')) {
            return [];
        }
        $streamParameters = $request->request->all('s');

        if (!isset($streamParameters[$stream->streamId])) {
            return [];
        }
        return $streamParameters[$stream->streamId];
    }
}
