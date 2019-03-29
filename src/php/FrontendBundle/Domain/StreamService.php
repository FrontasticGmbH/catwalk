<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
     * @param Stream $stream
     * @param array $parameters Extending the configuration of the stream
     * @return mixed
     */
    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!$stream->type) {
            return;
        }

        if (!isset($this->streamHandlers[$stream->type])) {
            return [
                'ok' => false,
                'message' => "No stream handler for stream type {$stream->type} configured.",
            ];
        }

        try {
            return $this->streamHandlers[$stream->type]->handle($stream, $context, $parameters);
        } catch (\Throwable $e) {
            return [
                'ok' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param Node $node
     * @param array $parameterMap Mapping stream IDs to parameter arrays
     * @return array
     */
    public function getStreamData(Node $node, Context $context, array $parameterMap = []): array
    {
        $data = [];
        foreach ($node->streams ?? [] as $stream) {
            $stream = new Stream($stream);
            $data[$stream->streamId] = $this->handle(
                $stream,
                $context,
                (isset($parameterMap[$stream->streamId]) ? $parameterMap[$stream->streamId] : [])
            );
        }

        return $data;
    }
}
