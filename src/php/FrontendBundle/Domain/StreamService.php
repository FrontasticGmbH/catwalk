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
    public function __construct(array $streamHandlers = [])
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
            throw new \OutOfBoundsException(
                "No stream handler for stream type {$stream->type} configured."
            );
        }

        return $this->streamHandlers[$stream->type]->handle($stream, $context, $parameters);
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
