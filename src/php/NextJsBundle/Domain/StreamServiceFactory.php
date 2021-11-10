<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Frontastic\Catwalk\NextJsBundle\Domain\StreamHandlerToDataSourceHandlerAdapter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;

class StreamServiceFactory
{
    private TasticService $tasticService;
    private HooksService $hooksService;
    private RequestService $requestService;
    private LoggerInterface $logger;
    private RequestStack $requestStack;

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
        HooksService $hooksService,
        RequestService $requestService,
        LoggerInterface $logger,
        RequestStack $requestStack,
        iterable $streamHandlers = [],
        iterable $streamOptimizers = [],
        bool $debug = false
    ) {
        $this->tasticService = $tasticService;
        $this->hooksService = $hooksService;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
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
            foreach ($this->hooksService->getRegisteredHooks() as $hook) {
                if (isset($hook['hookType']) && $hook['hookType'] === 'data-source') {
                    $streamService->addStreamHandlerV2(
                        $hook['dataSourceIdentifier'],
                        new StreamHandlerToDataSourceHandlerAdapter(
                            $this->hooksService,
                            $this->requestService,
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
