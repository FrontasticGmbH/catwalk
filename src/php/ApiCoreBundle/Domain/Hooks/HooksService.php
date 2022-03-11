<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\HttpClient;
use Frontastic\Common\JsonSerializer;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\EventListener\RequestIdListener;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\HttpClient\Response;

//TODO: RENAME TO EXTENSION SERVICE
class HooksService
{
    const DEFAULT_HEADERS = ['Content-Type: application/json'];
    const BASE_PATH = 'http://localhost:8082/'; // TODO: move to a config file later on
    const DYNAMIC_PAGE_HOOK = 'dynamic-page-handler';

    private ContextService $contextService;

    /** @var array[] */
    private ?array $hooks = null;

    private LoggerInterface $logger;

    private HookResponseDeserializer $hookResponseDeserializer;

    private RequestStack $requestStack;

    private HttpClient $httpClient;


    public function __construct(
        JsonSerializer $jsonSerializer,
        HookResponseDeserializer $hookResponseDeserializer,
        ContextService $contextService,
        RequestStack $requestStack,
        HttpClient $httpClient
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->hookResponseDeserializer = $hookResponseDeserializer;
        $this->contextService = $contextService;
        $this->requestStack = $requestStack;
        $this->httpClient = $httpClient;
    }

    public function fetchProjectHooks(string $project): array
    {
        $response = $this->httpClient->get($this->makePath('hooks', $project));

        if ($response->status != 200) {
            throw new \Exception(
                'Fetching available hooks failed. Error: ' . $response->body
            );
        }

        return Json::decode($response->body, true);
    }

    public function getHooks(): array
    {
        if ($this->hooks === null) {
            $this->hooks = $this->fetchProjectHooks($this->getProjectIdentifier());
        }

        return $this->hooks;
    }

    public function isHookRegistered(string $hook): bool
    {
        $hooks = $this->getHooks();

        return in_array($hook, array_keys($hooks), true);
    }

    public function callDataSource(string $hook, array $arguments)
    {
        return $this->callHook($hook, $arguments, true);
    }

    public function callDynamicPageHandler(array $arguments)
    {
        return $this->callHook(self::DYNAMIC_PAGE_HOOK, $arguments, false);
    }

    public function callAction(string $namespace, string $action, array $arguments)
    {
        $hookName = sprintf('action-%s-%s', $namespace, $action);
        return $this->callHook($hookName, $arguments, false);
    }

    public function hasDynamicPageHandler(): bool
    {
        return $this->isHookRegistered(self::DYNAMIC_PAGE_HOOK);
    }

    public function hasAction($namespace, $action): bool
    {
        $hookName = sprintf('action-%s-%s', $namespace, $action);
        return $this->isHookRegistered($hookName);
    }

    private function getProjectIdentifier(): string
    {
        $context = $this->contextService->createContextFromRequest();
        return $context->project->customer . '_' . $context->project->projectId;
    }

    private function callHook(string $hook, array $arguments, bool $async)
    {
        if (!$this->isHookRegistered($hook)) {
            return (object)[
                'ok' => false,
                'message' => sprintf('The requested hook "%s" was not found.', $hook)
            ];
        }

        $requestId = $this->requestStack->getCurrentRequest()->attributes->get(
            RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
        );

        $payload = Json::encode(['arguments' => $arguments]);

        $headers = ['Frontastic-Request-Id' => "Frontastic-Request-Id:$requestId"];

        try {
            $eventResult = $this->callEvent($this->getProjectIdentifier(), $hook, $payload, $headers, $async);
            return $async ? $eventResult : Json::decode($eventResult, false);
        } catch (\Exception $exception) {
            return (object)[
                'ok' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


    /**
     * @return string|PromiseInterface
     * @throws \Exception
     */
    private function callEvent(string $project, string $hookName, string $payload, ?array $headers, bool $async)
    {
        $path = $this->makePath('run', $project, $hookName);
        $requestHeaders = $headers + self::DEFAULT_HEADERS;

        if ($async) {
            return $this->httpClient->postAsync($path, $payload, $requestHeaders)->then(
                function (Response $response) use ($hookName) {
                    if ($response->status != 200) {
                        throw new \Exception('Calling hook ' . $hookName . ' failed. Error: ' . $response->body);
                    }

                    return $response->body;
                }
            );
        }

        $response = $this->httpClient->post($path, $payload, $requestHeaders);

        if ($response->status != 200) {
            throw new \Exception('Calling hook ' . $hookName . ' failed. Error: ' . $response->body);
        }

        return $response->body;
    }

    private function makePath(string ...$uri): string
    {
        return self::BASE_PATH . implode("/", $uri);
    }
}
