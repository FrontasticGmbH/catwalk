<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use Frontastic\Common\JsonSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HooksService
{
    private HooksApiClient $hooksApiClient;

    private JsonSerializer $jsonSerializer;

    private ContextService $contextService;

    /** @var object[] */
    private ?array $hooks = null;

    private LoggerInterface $logger;

    private HookResponseDeserializer $hookResponseDeserializer;

    private RequestStack $requestStack;

    public function __construct(
        HooksApiClient $hooksApiClient,
        JsonSerializer $jsonSerializer,
        HookResponseDeserializer $hookResponseDeserializer,
        ContextService $contextService,
        RequestStack $requestStack
    ) {
        $this->hooksApiClient = $hooksApiClient;
        $this->jsonSerializer = $jsonSerializer;
        $this->hookResponseDeserializer = $hookResponseDeserializer;
        $this->contextService = $contextService;
        $this->requestStack = $requestStack;
    }

    protected function isEventActive(string $eventName): bool
    {
        $hooks = $this->getRegisteredHooks();
        return in_array($eventName, array_column($hooks, 'hookName'), true);
    }

    protected function callRemoteHook(string $hook, array $arguments)
    {
        // TODO: Allow-list all parameter we want to actually pass over
        $context = $this->contextService->createContextFromRequest();
        $requestId = $this->requestStack->getCurrentRequest()->attributes->get(
            RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
        );

        $hookCallBuilder = new HooksCallBuilder([$this->jsonSerializer, 'serialize']);
        $hookCallBuilder->project($context->project->customer . '_' . $context->project->projectId);
        $hookCallBuilder->name($hook);
        $hookCallBuilder->context($context);
        $hookCallBuilder->arguments($arguments);
        $hookCallBuilder->header('Frontastic-Request-Id', $requestId);
        $call = $hookCallBuilder->build();

        $data = $this->hooksApiClient->callEvent($call);

        $response = Json::decode($data, true);

        if (!isset($response['arguments'])) {
            throw new \Exception('Invalid return format');
        }

        return $response;
    }

    public function call(string $hook, array $arguments)
    {
        if (!$this->isEventActive($hook)) {
            return null;
        }

        $response = $this->callRemoteHook($hook, $arguments);
        return $response;
    }

    public function callExpectArray(string $hook, array $arguments): ?array
    {
        if (!$this->isEventActive($hook)) {
            return null;
        }

        $response = $this->callRemoteHook($hook, $arguments);

        return $response['arguments'][0];
    }

    public function callExpectList(string $hook, array $arguments)
    {
        if (!$this->isEventActive($hook)) {
            return $arguments;
        }

        $response = $this->callRemoteHook($hook, $arguments);

        return array_map(
            function ($argument) {
                if (!is_array($argument)) {
                    return $argument;
                }
                return $this->hookResponseDeserializer->deserialize($argument);
            },
            $response['arguments']
        );
    }

    public function callExpectObject(string $hook, array $arguments)
    {
        if (!$this->isEventActive($hook)) {
            return null;
        }

        $response = $this->callRemoteHook($hook, $arguments);

        return $this->hookResponseDeserializer->deserialize($response['arguments'][0]);
    }

    public function callExpectMultipleObjects(string $hook, array $arguments)
    {
        if (!$this->isEventActive($hook)) {
            return null;
        }

        $response = $this->callRemoteHook($hook, $arguments);

        $formatedResponse = [];

        foreach ($response['arguments'] as $objectsData) {
            $formatedResponse = array_merge($formatedResponse, array_map(
                function ($objectData) {
                    return $this->hookResponseDeserializer->deserialize($objectData);
                },
                $objectsData
            ));
        }

        return $formatedResponse;
    }

    public function getRegisteredHooks(): array
    {
        if ($this->hooks === null) {
            $context = $this->contextService->createContextFromRequest();
            $this->hooks = $this->hooksApiClient->getHooks(
                $context->project->customer . '_' . $context->project->projectId
            );
        }
        return $this->hooks;
    }
}
