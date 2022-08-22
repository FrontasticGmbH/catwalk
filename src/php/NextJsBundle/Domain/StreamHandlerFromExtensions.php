<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\AppKernel;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerSupplier;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandlerV2;

class StreamHandlerFromExtensions implements StreamHandlerSupplier
{

    private ExtensionService $extensionService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $fromFrontasticReactMapper;
    private ContextCompletionService $contextCompletionService;

    public function __construct(
        ExtensionService $extensionService,
        RequestService $requestService,
        FromFrontasticReactMapper $fromFrontasticReactMapper,
        ContextCompletionService $contextCompletionService
    ) {
        $this->extensionService = $extensionService;
        $this->requestService = $requestService;
        $this->contextCompletionService = $contextCompletionService;
        $this->fromFrontasticReactMapper = $fromFrontasticReactMapper;
    }

    public function get(string $streamType): StreamHandlerV2
    {
        $dataSourceName = "data-source-" . str_replace("/", "-", $streamType);

        if (!AppKernel::isProduction() && !$this->extensionService->hasExtension($dataSourceName)) {
            throw new \RuntimeException("No extension for data source $dataSourceName configured.");
        }

        return new StreamHandlerToDataSourceHandlerAdapter(
            $this->extensionService,
            $this->requestService,
            $this->fromFrontasticReactMapper,
            $this->contextCompletionService,
            $dataSourceName
        );
    }
}
