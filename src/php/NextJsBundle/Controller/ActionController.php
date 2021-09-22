<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ActionController
{
    private HooksService $hooksService;
    private HttpKernelInterface $httpKernel;
    private string $rootDir;

    public function __construct(HooksService $hooksService, HttpKernelInterface $httpKernel, string $rootDir)
    {
        $this->hooksService = $hooksService;
        $this->httpKernel = $httpKernel;
        $this->rootDir = $rootDir;
    }

    public function indexAction(string $namespace, string $action, SymfonyRequest $request): JsonResponse
    {
        if ($this->hasOverride($namespace, $action)) {
            return $this->performOverrideForward($namespace, $action, $request);
        }

        $hookName = sprintf('action-%s-%s', $namespace, $action);

        // TODO: Extract and complete mapping
        $apiRequest = new Request();
        $apiRequest->query = (object) ($request->query->getIterator()->getArrayCopy());
        $apiRequest->path = $request->getPathInfo();
        $apiRequest->body = $request->getContent();
        $apiRequest->cookies = (object) ($request->cookies->all());

        /** @var Response $apiResponse */
        $apiResponse = $this->hooksService->call($hookName, [$apiRequest]);

        $response = new JsonResponse();
        $response->setContent($apiResponse->body);
        $response->setStatusCode($apiResponse->statusCode);
        // TODO pass other headers to JsonResponse

        return $response;
    }

    private function performOverrideForward(string $namespace, string $action, SymfonyRequest $request): SymfonyResponse
    {
        $overrides = $this->getOverrides();
        $controller = $overrides[$namespace][$action];

        $subRequest = $request->duplicate(null, null, ['_controller' => $controller]);

        return $this->httpKernel->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }

    private function hasOverride(string $namespace, string $action): bool
    {
        $overrides = $this->getOverrides();

        return (isset($overrides[$namespace]) && isset($overrides[$namespace][$action]));
    }

    private function getOverrides(): array
    {
        $overrideFile = sprintf('%s/config/action_override.json', $this->rootDir);

        if (!file_exists($overrideFile)) {
            return [];
        }

        return json_decode(file_get_contents($overrideFile), true);
    }
}
