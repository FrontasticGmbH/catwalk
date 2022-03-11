<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HooksService
{
    private HooksApiClient $hooksApiClient;

    private JsonSerializer $jsonSerializer;

    private ContextService $contextService;

    /** @var array[] */
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

    public function isHookRegistered(string $hook): bool
    {
        $hooks = $this->getRegisteredHooks();

        return in_array($hook, array_keys($hooks), true);
    }

    protected function callRemoteHook(string $hook, array $arguments)
    {
        $requestId = $this->requestStack->getCurrentRequest()->attributes->get(
            RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
        );

        $hookCall = new HooksCall(
            $this->getProjectIdentifier(),
            $hook,
            $arguments
        );
        $hookCall->addHeader('Frontastic-Request-Id', $requestId);

        try {
            $jsonString = $this->hooksApiClient->callEvent($hookCall);
            return json_decode($jsonString, false);
        } catch (\Exception $exception) {
            return (object)[
                'ok' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    protected function callRemoteHookAsync(string $hook, array $arguments, $mappingFunction): PromiseInterface
    {
        $requestId = $this->requestStack->getCurrentRequest()->attributes->get(
            RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
        );

        $hookCall = new HooksCall(
            $this->getProjectIdentifier(),
            $hook,
            $arguments
        );
        $hookCall->addHeader('Frontastic-Request-Id', $requestId);

        return $this->hooksApiClient->callEventAsync($hookCall, $mappingFunction);
    }

    public function call(string $hook, array $arguments)
    {
        if (!$this->isHookRegistered($hook)) {
            return (object)[
                'ok' => false,
                'message' => sprintf('The requested hook "%s" was not found.', $hook)
            ];
        }

        return $this->callRemoteHook($hook, $arguments);
    }

    function callAsync(string $hook, array $arguments, $callbackFunction) {
        if (!$this->isHookRegistered($hook)) {
            return (object)[
                'ok' => false,
                'message' => sprintf('The requested hook "%s" was not found.', $hook)
            ];
        }

        return $this->callRemoteHookAsync($hook, $arguments, $callbackFunction);
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

    private function getProjectIdentifier(): string
    {
        $context = $this->contextService->createContextFromRequest();
        return $context->project->customer . '_' . $context->project->projectId;
    }
}
