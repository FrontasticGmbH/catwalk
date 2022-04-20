<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context as ClassicContext;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\ActionContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\ContextCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionController
{
    private ExtensionService $extensionService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $mapper;
    private ContextCompletionService $contextCompletionService;
    private bool $debug;

    public function __construct(
        ExtensionService $extensionService,
        RequestService $requestService,
        FromFrontasticReactMapper $mapper,
        ContextCompletionService $contextCompletionService,
        bool $debug = false
    ) {
        $this->extensionService = $extensionService;
        $this->requestService = $requestService;
        $this->mapper = $mapper;
        $this->debug = $debug;
        $this->contextCompletionService = $contextCompletionService;
    }

    public function indexAction(
        string $namespace,
        string $action,
        SymfonyRequest $request,
        ClassicContext $context
    ): JsonResponse {
        $isProduction = $context->isProduction();
        $apiRequest = $this->requestService->createApiRequest($request);
        $actionContext = $this->createActionContext($context);

        $this->assertActionExists($namespace, $action);

        $timeout = $actionContext->frontasticContext->project->configuration["extensions"]["actionTimeout"] ?? null;

        /** @var \stdClass $apiResponse */
        $apiResponse = $this->extensionService->callAction(
            $namespace,
            $action,
            [$apiRequest, $actionContext],
            $timeout
        );

        $response = new JsonResponse();

        if (property_exists($apiResponse, 'sessionData')) {
            if ($apiResponse->sessionData === null) {
                $this->clearJwtSession($response);
            } else {
                $this->storeJwtSession($response, $apiResponse->sessionData, $isProduction);
            }
        } else {
            // send a null payload
            $this->storeJwtSession($response, $apiRequest->sessionData, $isProduction);
        }

        if (isset($apiResponse->ok) && !$apiResponse->ok) {
            // hooksservice signaled an error
            $response->setStatusCode(500);
            $response->setContent(json_encode((object)$apiResponse));
        } elseif (!isset($apiResponse->statusCode) || !isset($apiResponse->body)) {
            // Fixme: Make all extensions return a valid response!
            $response->setStatusCode(200);
            $response->headers->set(
                'X-Extension-Error',
                'Data returned from hook did not have statusCode or body fields'
            );
            $response->setContent(json_encode((object)$apiResponse));
        } else {
            $response->setContent($apiResponse->body);
            $response->setStatusCode($apiResponse->statusCode);
            // TODO pass other headers to JsonResponse
        }

        return $response;
    }

    private function createActionContext(ClassicContext $classicContext): ActionContext
    {
        /** @var Context $context */
        $context = $this->mapper->map($classicContext);
        $context = $this->contextCompletionService->completeContextData($context, $classicContext);
        return new ActionContext(['frontasticContext' => $context]);
    }

    /**
     * @param JsonResponse $response
     * @return void
     */
    private function clearJwtSession(JsonResponse $response): void
    {
        $response->headers->set('frontastic-session', null);
    }

    /**
     * @param JsonResponse $response
     * @param $sessionData
     * @return void
     */
    private function storeJwtSession(JsonResponse $response, $sessionData, bool $isProduction): void
    {
        $jwt = $this->requestService->encodeJWTData($sessionData, $isProduction);

        $response->headers->set(
            'frontastic-session',
            $jwt
        );
    }

    private function assertActionExists(string $namespace, string $action)
    {
        if ($this->extensionService->hasAction($namespace, $action)) {
            return;
        }

        $errorMessage = sprintf(
            'Action "%s" in namespace "%s" is not registered.',
            $action,
            $namespace
        );

        if ($this->debug) {
            $errorMessage .= ' Registered actions are: ' .
                implode(
                    ', ',
                    array_map(
                        function (array $actionHook) {
                            return sprintf(
                                '%s/%s',
                                $actionHook['actionNamespace'] ?? 'UNKNOWN-NAMESPACE',
                                $actionHook['actionIdentifier'] ?? 'UNKNOWN-IDENTIFIER'
                            );
                        },
                        array_filter(
                            $this->extensionService->getExtensions(),
                            function (array $hook) {
                                return (isset($hook['hookType']) && $hook['hookType'] === 'action');
                            }
                        )
                    )
                );
        }

        throw new BadRequestHttpException($errorMessage);
    }
}
