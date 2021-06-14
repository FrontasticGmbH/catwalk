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

    /** @var string[] */
    private ?array $hooks = null;

    private LoggerInterface $logger;

    private HookResponseDeserializer $hookResponseDeserializer;

    private RequestStack $requestStack;

    public function __construct(
        HooksApiClient $hooksApiClient,
        JsonSerializer $jsonSerializer,
        HookResponseDeserializer $hookResponseDeserializer,
        ContextService $contextService,
        RequestStack $requestStack,
        LoggerInterface $logger
    ) {
        $this->hooksApiClient = $hooksApiClient;
        $this->jsonSerializer = $jsonSerializer;
        $this->hookResponseDeserializer = $hookResponseDeserializer;
        $this->contextService = $contextService;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    protected function isEventActive(string $eventName): bool
    {
        $context = $this->contextService->createContextFromRequest();
        if ($this->hooks === null) {
            $this->hooks = $this->hooksApiClient->getHooks(
                $context->project->customer . '_' . $context->project->projectId
            );
        }
        return in_array($eventName, array_column($this->hooks, 'hookName'), true);
    }

    protected function callRemoteHook(string $hook, array $arguments)
    {
        $start = microtime(true);

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

        $this->log($start, $call->getName());

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

    private function log(float $startTime, string $eventName)
    {
        $time = microtime(true) - $startTime;

        $this->logger->info(
            sprintf(
                'Hook for %s took %dms',
                $eventName,
                $time * 1000
            ),
            [
                'hookEvent' => [
                    'event' => $eventName,
                    'timeToComplete' => $time,
                ],
            ]
        );
    }
}
