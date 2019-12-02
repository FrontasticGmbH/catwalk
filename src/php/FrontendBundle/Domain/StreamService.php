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
        'product-list' => [
            // Boost Theme
            'productCount',
            // From Apollo
            'maxItems',
        ],
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

    private function findUsageInConfiguration(Tastic $tastic, array $fields, array $configuration, array $usage): array
    {
        foreach ($fields as $field) {
            if ($field['type'] === 'stream' &&
                !empty($configuration[$field['field']])) {
                $usage[$configuration[$field['field']]][] = null;

                foreach ($this->countProperties[$field['streamType']] ?? [] as $countFieldName) {
                    $usage[$configuration[$field['field']]][] = $configuration[$countFieldName] ?? null;
                }
            }

            if ($field['type'] === 'group' &&
                !empty($configuration[$field['field']]) &&
                is_array($configuration[$field['field']])) {
                foreach ($configuration[$field['field']] as $fieldConfiguration) {
                    // Recurse into groups
                    $usage = $this->findUsageInConfiguration(
                        $tastic,
                        $field['fields'],
                        $fieldConfiguration,
                        $usage
                    );
                }
            }
        }

        return $usage;
    }

    private function updateUsage(Tastic $tastic, array $schema, array $usage): array
    {
        foreach ($schema as $group) {
            $usage = $this->findUsageInConfiguration(
                $tastic,
                $group['fields'],
                (array) $tastic->configuration,
                $usage
            );
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
        foreach ($node->streams ?? [] as $stream) {
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
            $streams = $node->streams ?? [];
        }

        $data = [];
        foreach ($streams as $stream) {
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
