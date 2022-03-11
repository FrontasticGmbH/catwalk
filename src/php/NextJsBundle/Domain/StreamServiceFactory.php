<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class StreamServiceFactory
{
    private TasticService $tasticService;
    private ExtensionService $extensionService;
    private RequestService $requestService;
    private LoggerInterface $logger;
    private RequestStack $requestStack;
    private FromFrontasticReactMapper $fromFrontasticReactMapper;
    private ContextCompletionService $contextCompletionService;

    /**
     * @var StreamHandlerV2[]
     */
    private iterable $streamHandlers;

    /**
     * @var StreamOptimizer[]
     */
    private iterable $streamOptimizers;

    private bool $debug;

    public function __construct(
        TasticService $tasticService,
        ExtensionService $extensionService,
        RequestService $requestService,
        LoggerInterface $logger,
        RequestStack $requestStack,
        FromFrontasticReactMapper $fromFrontasticReactMapper,
        ContextCompletionService $contextCompletionService,
        iterable $streamHandlers = [],
        iterable $streamOptimizers = [],
        bool $debug = false
    ) {
        $this->tasticService = $tasticService;
        $this->extensionService = $extensionService;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->fromFrontasticReactMapper = $fromFrontasticReactMapper;
        $this->contextCompletionService = $contextCompletionService;
        $this->streamHandlers = $streamHandlers;
        $this->streamOptimizers = $streamOptimizers;
        $this->debug = $debug;
        $this->requestService = $requestService;
    }

    public function create(): StreamService
    {
        $streamService = new StreamService(
            $this->tasticService,
            $this->logger,
            $this->requestStack,
            $this->streamHandlers,
            $this->streamOptimizers,
            $this->debug
        );

        try {
            foreach ($this->extensionService->getExtensions() as $hook) {
                if (isset($hook['hookType']) && $hook['hookType'] === 'data-source') {
                    $streamService->addStreamHandlerV2(
                        $hook['dataSourceIdentifier'],
                        new StreamHandlerToDataSourceHandlerAdapter(
                            $this->extensionService,
                            $this->requestService,
                            $this->fromFrontasticReactMapper,
                            $this->contextCompletionService,
                            $hook['hookName']
                        )
                    );
                }
            }
        } catch (\Throwable $e) {
            // EAT for now
            // FIXME: log!!!
        }

        return $streamService;
    }
}
