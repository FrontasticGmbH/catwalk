<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamOptimizer;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class StreamServiceFactory
{
    private TasticService $tasticService;
    private LoggerInterface $logger;
    private RequestStack $requestStack;
    private StreamHandlerFromExtensions $streamHandlerFromExtensions;

    /**
     * @var StreamOptimizer[]
     */
    private iterable $streamOptimizers;

    private bool $debug;

    public function __construct(
        TasticService $tasticService,
        LoggerInterface $logger,
        RequestStack $requestStack,
        StreamHandlerFromExtensions $streamHandlerFromExtensions,
        iterable $streamOptimizers = [],
        bool $debug = false
    ) {
        $this->tasticService = $tasticService;
        $this->logger = $logger;
        $this->requestStack = $requestStack;
        $this->streamHandlerFromExtensions = $streamHandlerFromExtensions;
        $this->streamOptimizers = $streamOptimizers;
        $this->debug = $debug;
    }

    public function create(): StreamService
    {
        return new StreamService(
            $this->tasticService,
            $this->logger,
            $this->requestStack,
            $this->streamHandlerFromExtensions,
            $this->streamOptimizers,
            $this->debug,
        );
    }
}
