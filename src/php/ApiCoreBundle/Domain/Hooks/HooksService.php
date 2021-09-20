<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
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

    protected function isHookRegistered(string $hook): bool
    {
        $hooks = $this->getRegisteredHooks();
        return in_array($hook, array_column($hooks, 'hookName'), true);
    }

    protected function callRemoteHook(string $hook, array $arguments): array
    {
        // TODO: Allow-list all parameter we want to actually pass over
        $context = $this->contextService->createContextFromRequest();
        $requestId = $this->requestStack->getCurrentRequest()->attributes->get(
            RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
        );

        $hookCallBuilder = new HooksCallBuilder(
            [$this->jsonSerializer, 'serialize']
        );
        $hookCallBuilder->project(
            $context->project->customer . '_' . $context->project->projectId
        );
        $hookCallBuilder->name($hook);
        $hookCallBuilder->context($context);
        $hookCallBuilder->arguments($arguments);
        $hookCallBuilder->header('Frontastic-Request-Id', $requestId);
        $call = $hookCallBuilder->build();

        try {
            $data = $this->hooksApiClient->callEvent($call);
        } catch (\Exception $exception) {
            return [
                'ok' => false,
                'found' => true,
                'message' => $exception->getMessage()
            ];
        }

        return [
            'ok' => true,
            'data' => $data
        ];
    }

    public function call(string $hook, array $arguments): array
    {
        if (!$this->isHookRegistered($hook)) {
            return [
                'ok' => false,
                'found' => false,
                'message' => sprintf('The requested hook "%s" was not found.', $hook)
            ];
        }

        return $this->callRemoteHook($hook, $arguments);
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
