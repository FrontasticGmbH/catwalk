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

    protected function isHookRegistered(string $hook): bool
    {
        $hooks = $this->getRegisteredHooks();
        return in_array($hook, array_column($hooks, 'hookName'), true);
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
            $jsonString = $this->hooksApiClient->callEvent($call);
            // deserialize to Response string, using the symfony deserializer maybe?
            $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
            $response = $serializer->deserialize($jsonString, Response::class, 'json');
        } catch (\Exception $exception) {
            $errorResponse = new Response();
            $errorResponse->statusCode = '500';
            $errorResponse->body = json_encode(
                [
                    'ok' => false,
                    'message' => $exception->getMessage()
                ]
            );
            return $errorResponse;
        }

        return $response;
    }

    public function call(string $hook, array $arguments): Response
    {
        if (!$this->isHookRegistered($hook)) {
            $errorResponse = new Response();
            $errorResponse->statusCode = '404';
            $errorResponse->body = json_encode(
                [
                    'ok' => false,
                    'message' => sprintf('The requested hook "%s" was not found.', $hook)
                ]
            );
            return $errorResponse;
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
