<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class StreamService
{
    /**
     * @var StreamHandler[]
     */
    private $streamHandlers = [];

    /**
     * @param StreamHandler[]
     */
    public function __construct(iterable $streamHandlers = [])
    {
        foreach ($streamHandlers as $streamHandler) {
            $this->addStreamHandler($streamHandler);
        }
    }

    public function addStreamHandler(StreamHandler $streamHandler)
    {
        $this->streamHandlers[$streamHandler->getType()] = $streamHandler;
    }

    /**
     * @param Node $node
     * @param array $parameterMap Mapping stream IDs to parameter arrays
     * @return array
     * @throws \Throwable
     */
    public function getStreamData(Node $node, Context $context, array $parameterMap = []): array
    {
        $data = [];
        foreach ($node->streams ?? [] as $stream) {
            $stream = new Stream($stream);
            $data[$stream->streamId] = $this
                ->handle(
                    $stream,
                    $context,
                    (isset($parameterMap[$stream->streamId]) ? $parameterMap[$stream->streamId] : [])
                )
                ->otherwise(function (\Exception $exception) {
                    return [
                        'ok' => false,
                        'message' => $exception->getMessage(),
                    ];
                });
        }

        return Promise\unwrap($data);
    }

    /**
     * @param Stream $stream
     * @param array $parameters Extending the configuration of the stream
     * @return PromiseInterface
     */
    private function handle(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        if (!$stream->type) {
            return Promise\rejection_for(
                new \RuntimeException('The stream has no type')
            );
        }

        if (!isset($this->streamHandlers[$stream->type])) {
            return Promise\rejection_for(
                new \RuntimeException("No stream handler for stream type {$stream->type} configured.")
            );
        }

        try {
            return $this->streamHandlers[$stream->type]->handleAsync($stream, $context, $parameters);
        } catch (\Exception $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
