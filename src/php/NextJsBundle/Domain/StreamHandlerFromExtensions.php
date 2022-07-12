<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;

class StreamHandlerFromExtensions
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

    public function fetch(): array
    {
        $result = [];
        try {
            foreach ($this->extensionService->getExtensions() as $hook) {
                if (isset($hook['hookType']) && $hook['hookType'] === 'data-source') {
                    $result[$hook['dataSourceIdentifier']] =
                        new StreamHandlerToDataSourceHandlerAdapter(
                            $this->extensionService,
                            $this->requestService,
                            $this->fromFrontasticReactMapper,
                            $this->contextCompletionService,
                            $hook['hookName']
                        );
                }
            }
        } catch (\Throwable $e) {
            // EAT for now
            // FIXME: log!!!
        }

        return $result;
    }
}
