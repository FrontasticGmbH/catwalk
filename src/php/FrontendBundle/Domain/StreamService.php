<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic as TasticModel;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\NextJsBundle\Domain\StreamHandlerFromExtensions;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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

    private RequestStack $requestStack;

    /**
     * @var StreamHandlerV2[]
     */
    private $streamHandlers = [];

    /**
     * @var StreamOptimizer[]
     */
    private $streamOptimizers = [];

    private StreamHandlerFromExtensions $streamHandlerFromExtensions;

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
     * @param StreamHandler[] $streamHandlers Only "legacy" stream handlers go here, StreamHandlerV2 please
     * go to {@link self::addStreamHandlerV2()}.
     */
    public function __construct(
        TasticService $tasticService,
        LoggerInterface $logger,
        RequestStack $requestStack,
        StreamHandlerFromExtensions $fetchStreamHandlersFunction,
        iterable $streamOptimizers = [],
        bool $debug = false
    ) {
        $this->tasticService = $tasticService;
        $this->logger = $logger;
        $this->requestStack = $requestStack;

        foreach ($streamOptimizers as $streamOptimizer) {
            $this->addStreamOptimizer($streamOptimizer);
        }

        $this->streamHandlerFromExtensions = $fetchStreamHandlersFunction;

        $this->debug = $debug;
    }

    /**
     * @param StreamHandler $streamHandler
     * @deprecated Use addStreamHandlerV2 instead
     */
    public function addStreamHandler(StreamHandler $streamHandler)
    {
        $this->addStreamHandlerV2($streamHandler->getType(), new StreamHandlerV2Adapter($streamHandler));
    }

    public function addStreamHandlerV2(string $streamType, StreamHandlerV2 $streamHandler): void
    {
        $this->streamHandlers[$streamType] = $streamHandler;
    }

    public function addStreamOptimizer(StreamOptimizer $streamOptimizer)
    {
        $this->streamOptimizers[] = $streamOptimizer;
    }

    private function findUsageInConfiguration(Tastic $tastic, array $fields, array $configuration, array $usage): array
    {
        foreach ($fields as $field) {
            if (($field['type'] === 'stream' || $field['type'] == 'dataSource') &&
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

                $streamType = $field['streamType'] ?? $field['dataSourceType'];
                foreach ($this->countProperties[$streamType] ?? [] as $countFieldName) {
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
                            $streamType = $field['streamType'] ?? $field['dataSourceType'] ?? null;
                            if ($field['type'] === 'stream' &&
                                empty($tastic->configuration->{$field['field']}) &&
                                $streamType !== null &&
                                isset($defaultStreams[$streamType])
                            ) {
                                $tastic->configuration->{$field['field']} =
                                    $defaultStreams[$streamType]['streamId'];
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
            'request' => $this->requestStack->getCurrentRequest(),
        ]);

        foreach ($streams as $stream) {
            $stream = new Stream($stream);
            $streamContext->parameters = isset($parameterMap[$stream->streamId]) ?
                $parameterMap[$stream->streamId] :
                [];

            $data[$stream->streamId] = $this
                ->handle(
                    $stream,
                    $streamContext
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

    private function handle(Stream $stream, StreamContext $streamContext): PromiseInterface
    {
        if (!$stream->type) {
            return Promise\rejection_for(
                new \RuntimeException('The stream has no type')
            );
        }

        // There's no need to execute the stream handler if the value is preloaded
        // The preloadedValue can be fetched in the DynamicPageService when getting the Node
        if ($stream->preloadedValue !== null) {
            return Promise\promise_for($stream->preloadedValue);
        }

        if (empty($this->streamHandlers) && isset($this->streamHandlerFromExtensions)) {
            foreach ($this->streamHandlerFromExtensions->fetch() as $key => $value) {
                $this->addStreamHandlerV2($key, $value);
            }
        }

        if (!isset($this->streamHandlers[$stream->type])) {
            return Promise\rejection_for(
                new \RuntimeException("No stream handler for stream type {$stream->type} configured.")
            );
        }

        if (isset($streamContext->parameters['streamContent'])) {
            return Promise\promise_for($streamContext->parameters['streamContent']);
        }

        try {
            return $this->streamHandlers[$stream->type]->handle($stream, $streamContext);
        } catch (\Throwable $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
