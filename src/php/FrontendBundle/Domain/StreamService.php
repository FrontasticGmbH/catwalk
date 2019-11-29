<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class StreamService
{
    /**
     * @var TasticService
     */
    private $tasticService;

    /**
     * @var StreamHandler[]
     */
    private $streamHandlers = [];

    /**
     * @var bool
     */
    private $debug = false;

    private $countProperties = [
        // From Apollo
        'uni-product-slider' => 'productCount',
        'uni-product-list' => 'maxItems',
        'product-list' => 'maxItems',

        // Boost Theme
        'frontastic/boost/product-slider' => 'productCount',
    ];

    /**
     * @param StreamHandler[]
     */
    public function __construct(TasticService $tasticService, iterable $streamHandlers = [], bool $debug = false)
    {
        $this->tasticService = $tasticService;
        foreach ($streamHandlers as $streamHandler) {
            $this->addStreamHandler($streamHandler);
        }
        $this->debug = $debug;
    }

    public function addStreamHandler(StreamHandler $streamHandler)
    {
        $this->streamHandlers[$streamHandler->getType()] = $streamHandler;
    }

    private function updateUsage(Tastic $tastic, array $schema, array $usage): array
    {
        foreach ($schema as $group) {
            foreach ($group['fields'] as $field) {
                if ($field['type'] === 'stream' &&
                    !empty($tastic->configuration->{$field['field']})) {
                    $usage[$tastic->configuration->{$field['field']}][] =
                        isset($this->countProperties[$tastic->tasticType]) ?
                            $tastic->configuration->{$this->countProperties[$tastic->tasticType]} :
                            null;
                }
            }
        }

        return $usage;
    }

    public function getUsedStreams(Node $node, Page $page, array &$parameterMap): array
    {
        $tasticMap = $this->tasticService->getTasticsMappedByType();

        $usage = [];
        foreach ($page->regions as $region) {
            foreach ($region->elements as $cell) {
                foreach ($cell->tastics as $tastic) {
                    if (!isset($tasticMap[$tastic->tasticType])) {
                        continue;
                    }

                    $schema = $tasticMap[$tastic->tasticType]->configurationSchema['schema'];
                    $usage = $this->updateUsage($tastic, $schema, $usage);
                }
            }
        }

        $usage = array_map('max', $usage);

        $streams = [];
        foreach ($node->streams as $stream) {
            if (!array_key_exists($stream['streamId'], $usage)) {
                continue;
            }

            $streams[] = $stream;

            if (!$usage[$stream['streamId']]) {
                // No count definition found for stream
                continue;
            }

            if (!isset($parameterMap[$stream['streamId']])) {
                $parameterMap[$stream['streamId']] = [];
            }
            $parameterMap[$stream['streamId']]['limit'] = $usage[$stream['streamId']];
        }

        return $streams;
    }

    /**
     * @param Node $node
     * @param array $parameterMap Mapping stream IDs to parameter arrays
     * @return array
     * @throws \Throwable
     */
    public function getStreamData(Node $node, Context $context, array $parameterMap = [], Page $page = null): array
    {
        if ($page) {
            $streams = $this->getUsedStreams($node, $page, $parameterMap);
        } else {
            $streams = $node->streams;
        }

        $data = [];
        foreach ($streams ?? [] as $stream) {
            $stream = new Stream($stream);
            $data[$stream->streamId] = $this
                ->handle(
                    $stream,
                    $context,
                    (isset($parameterMap[$stream->streamId]) ? $parameterMap[$stream->streamId] : [])
                )
                ->otherwise(function (\Throwable $exception) {
                    $errorResult = [
                        'ok' => false,
                        'message' => $exception->getMessage(),
                    ];
                    if ($this->debug) {
                        $errorResult['trace'] = $exception->getTrace();
                        $errorResult['file'] = $exception->getFile();
                        $errorResult['line'] = $exception->getLine();
                    }
                    return $errorResult;
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
        } catch (\Throwable $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
