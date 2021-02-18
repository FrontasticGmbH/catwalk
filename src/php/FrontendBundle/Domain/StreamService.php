<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic as TasticModel;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;

class StreamService
{
    /**
     * @var TasticService
     */
    private $tasticService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StreamHandler[]
     */
    private $streamHandlers = [];

    /**
     * @var StreamOptimizer[]
     */
    private $streamOptimizers = [];

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * This property is a workaround
     *
     * We want to have the count configured per stream using tastic directly in
     * backstage which will remove the necessity of adding a custom count
     * property to the tastic itself. Until we have that this is an include
     * list of properties which we use for the count until then. If customers
     * come up with additional count properties in the mean time, we should add
     * them here to enable this performance optimization for them, too.
     *
     * @var [string=>string[]]
     */
    private $countProperties = [
        'product-list' => [
            'productCount',
            // For Apollo
            'maxItems',
        ],
        'content-list' => [
            'contentCount',
        ],
    ];

    /**
     * @param StreamHandler[]
     */
    public function __construct(
        TasticService $tasticService,
        LoggerInterface $logger,
        iterable $streamHandlers = [],
        iterable $streamOptimizers = [],
        bool $debug = false
    ) {
        $this->tasticService = $tasticService;
        $this->logger = $logger;
        foreach ($streamHandlers as $streamHandler) {
            $this->addStreamHandler($streamHandler);
        }
        foreach ($streamOptimizers as $streamOptimizer) {
            $this->addStreamOptimizer($streamOptimizer);
        }
        $this->debug = $debug;
    }

    public function addStreamHandler(StreamHandler $streamHandler)
    {
        $this->streamHandlers[$streamHandler->getType()] = $streamHandler;
    }

    public function addStreamOptimizer(StreamOptimizer $streamOptimizer)
    {
        $this->streamOptimizers[] = $streamOptimizer;
    }

    private function findUsageInConfiguration(Tastic $tastic, array $fields, array $configuration, array $usage): array
    {
        foreach ($fields as $field) {
            if ($field['type'] === 'stream' &&
                (!empty($configuration[$field['field']]) || isset($field['default']))
            ) {
                $streamId = $configuration[$field['field']] ?? $field['default'];

                if (!is_string($streamId)) {
                    if (is_string($field['default'] ?? null)) {
                        $streamId = $field['default'];
                    } else {
                        continue;
                    }
                }

                $usage[$streamId]['count'][] = null;
                $usage[$streamId]['tastics'][] = $tastic->tasticType;

                foreach ($this->countProperties[$field['streamType']] ?? [] as $countFieldName) {
                    $usage[$streamId]['count'][] = $configuration[$countFieldName] ?? null;
                    $usage[$streamId]['tastics'][] = $tastic->tasticType;
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
                (array)$tastic->configuration,
                $usage
            );
        }

        return $usage;
    }

    public function getUsedStreams(Node $node, Page $page, array &$parameterMap): array
    {
        $tasticMap = $this->tasticService->getTasticsMappedByType();

        $usage = [
            '__master' => ['tastics' => []],
        ];
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

        foreach ($usage as $streamId => $usages) {
            $usage[$streamId]['count'] = !empty($usages['count']) ? max($usages['count']) : null;
            $usage[$streamId]['tastics'] = array_values(array_filter(array_unique($usages['tastics'])));
        }

        $streams = [];
        foreach ($node->streams ?? [] as $stream) {
            if (!array_key_exists($stream['streamId'], $usage)) {
                continue;
            }

            $stream['tastics'] = array_map(
                function (string $tasticType) use ($tasticMap): TasticModel {
                    return $tasticMap[$tasticType];
                },
                $usage[$stream['streamId']]['tastics']
            );
            $streams[] = $stream;

            if (!$usage[$stream['streamId']]['count']) {
                // No count definition found for stream
                continue;
            }

            if (!isset($parameterMap[$stream['streamId']])) {
                $parameterMap[$stream['streamId']] = [];
            }
            $parameterMap[$stream['streamId']]['limit'] = $usage[$stream['streamId']]['count'];
        }

        return $streams;
    }

    public function completeDefaultStreams(Node $node, Page $page): Page
    {
        $tasticMap = $this->tasticService->getTasticsMappedByType();

        $defaultStreams = [];
        foreach ($node->streams as $stream) {
            if (!isset($stream['type'])) {
                continue;
            }

            if (!isset($defaultStreams[$stream['type']])) {
                $defaultStreams[$stream['type']] = $stream;
            }
        }

        foreach ($page->regions as $region) {
            foreach ($region->elements as $cell) {
                foreach ($cell->tastics as $tastic) {
                    if (!isset($tasticMap[$tastic->tasticType])) {
                        continue;
                    }

                    // @TODO: Recurse into groups
                    $schema = $tasticMap[$tastic->tasticType]->configurationSchema['schema'];
                    foreach ($schema as $group) {
                        foreach ($group['fields'] as $field) {
                            if ($field['type'] === 'stream' &&
                                empty($tastic->configuration->{$field['field']}) &&
                                isset($defaultStreams[$field['streamType']])
                            ) {
                                $tastic->configuration->{$field['field']} =
                                    $defaultStreams[$field['streamType']]['streamId'];
                            }
                        }
                    }
                }
            }
        }

        return $page;
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
            $page = $this->completeDefaultStreams($node, $page);
            $streams = $this->getUsedStreams($node, $page, $parameterMap);
        } else {
            $streams = $node->streams ?? [];
        }

        $data = [];
        $streamContext = new StreamContext([
            'node' => $node,
            'page' => $page,
            'context' => $context,
        ]);
        foreach ($streams as $stream) {
            $stream = new Stream($stream);
            $streamContext->parameters = isset($parameterMap[$stream->streamId]) ?
                $parameterMap[$stream->streamId] :
                [];

            $data[$stream->streamId] = $this
                ->handle(
                    $stream,
                    $context,
                    $streamContext->parameters
                )
                ->otherwise(function (\Throwable $exception) use ($stream) {
                    $errorResult = [
                        'ok' => false,
                        'message' => $exception->getMessage(),
                    ];
                    if ($this->debug) {
                        $errorResult['trace'] = $exception->getTrace();
                        $errorResult['file'] = $exception->getFile();
                        $errorResult['line'] = $exception->getLine();

                        debug(
                            sprintf('Error fetching data for stream %s (type %s)', $stream->streamId, $stream->type),
                            [
                                'message' => $exception->getMessage(),
                                'file' => $exception->getFile(),
                                'line' => $exception->getLine(),
                                // Don't include the `$exception->getTrace()` here since it is not always cloneable.
                            ]
                        );

                        $this->logger->warning(
                            sprintf(
                                'Error fetching data for stream %s (type %s): %s',
                                $stream->streamId,
                                $stream->type,
                                $exception->getMessage()
                            ),
                            [
                                'file' => $exception->getFile(),
                                'line' => $exception->getLine(),
                            ]
                        );
                    }
                    return $errorResult;
                });
        }

        $data = Promise\unwrap($data);
        foreach ($streams as $stream) {
            $stream = new Stream($stream);
            $streamContext->usingTastics = $stream->tastics;

            foreach ($this->streamOptimizers as $streamOptimizer) {
                $data[$stream->streamId] = $streamOptimizer->optimizeStreamData(
                    $stream,
                    $streamContext,
                    $data[$stream->streamId]
                );
            }
        }

        return $data;
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

        if (isset($parameters['streamContent'])) {
            return Promise\promise_for($parameters['streamContent']);
        }

        try {
            return $this->streamHandlers[$stream->type]->handleAsync($stream, $context, $parameters);
        } catch (\Throwable $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
