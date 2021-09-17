<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
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

    protected function callRemoteHook(string $hook, array $arguments): Response
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
            $errorResponse = new Response();
            $errorResponse->statusCode = '500';
            $errorResponse->body = $exception->getMessage();
            return $errorResponse;
        }

        $response = new Response();
        $response->statusCode = '200';
        $response->body = $data;

        return $response;
    }

    public function call(string $hook, array $arguments): Response
    {
        if (!$this->isEventActive($hook)) {
            $errorResponse = new Response();
            $errorResponse->statusCode = '404';
            $errorResponse->body = 'The requested hook is not active.';
            return $errorResponse;
        }

        return $this->callRemoteHook($hook, $arguments);
    }

    public function knowsHook(string $hookName): bool
    {
        return $this->isEventActive($hookName);
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
