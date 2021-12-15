<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
