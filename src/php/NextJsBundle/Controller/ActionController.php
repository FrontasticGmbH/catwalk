<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\ActionContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class ActionController
{
    private HooksService $hooksService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $mapper;

    public function __construct(
        HooksService $hooksService,
        RequestService $requestService,
        FromFrontasticReactMapper $mapper
    ) {
        $this->hooksService = $hooksService;
        $this->requestService = $requestService;
        $this->mapper = $mapper;
    }

    public function indexAction(
        string $namespace,
        string $action,
        SymfonyRequest $request,
        Context $context
    ): JsonResponse {
        $hookName = sprintf('action-%s-%s', $namespace, $action);

        $apiRequest = $this->requestService->createApiRequest($request);
        $actionContext = $this->createActionContext($context);

        /** @var stdClass $apiResponse */
        $apiResponse = $this->hooksService->call($hookName, [$apiRequest, $actionContext]);

        $response = new JsonResponse();

        if (property_exists($apiResponse, 'sessionData')) {
            if ($apiResponse->sessionData === null) {
                $this->clearJwtSession($response);
            } else {
                $this->storeJwtSession($response, $apiResponse->sessionData);
            }
        } else {
            $this->storeJwtSession($response, $apiRequest->sessionData);
        }

        if (isset($apiResponse->ok) && !$apiResponse->ok) {
            // hooksservice signaled an error
            $response->setStatusCode(500);
            $response->setContent(json_encode((object) $apiResponse));
        } elseif (!isset($apiResponse->statusCode) || !isset($apiResponse->body)) {
            // Fixme: Make all extensions return a valid response!
            $response->setStatusCode(200);
            $response->headers->set(
                'X-Extension-Error',
                'Data returned from hook did not have statusCode or body fields'
            );
            $response->setContent(json_encode((object) $apiResponse));
        } else {
            $response->setContent($apiResponse->body);
            $response->setStatusCode($apiResponse->statusCode);
            // TODO pass other headers to JsonResponse
        }

        return $response;
    }

    private function createActionContext(Context $context): ActionContext
    {
        return new ActionContext(['frontasticContext' => $this->mapper->map($context)]);
    }

    /**
     * @param JsonResponse $response
     * @return void
     */
    private function clearJwtSession(JsonResponse $response): void
    {
        $response->headers->clearCookie(
            'frontastic-session',
            '/',
            null,
            true,
            true,
            "none"
        );
    }

    /**
     * @param JsonResponse $response
     * @param $sessionData
     * @return void
     */
    private function storeJwtSession(JsonResponse $response, $sessionData): void
    {
        $response->headers->setCookie(
            new Cookie(
                'frontastic-session',
                $this->requestService->encodeJWTData($sessionData),
                (new \DateTime())->add(new \DateInterval('P30D')),
                '/',
                null,
                true,
                true,
                false,
                "none"
            )
        );
    }
}
