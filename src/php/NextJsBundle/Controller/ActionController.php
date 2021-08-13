<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionController
{
    private HooksService $hooksService;

    public function __construct(HooksService $hooksService)
    {
        $this->hooksService = $hooksService;
    }

    public function indexAction(string $namespace, string $action, SymfonyRequest $request)
    {
        $hookName = sprintf('action-%s-%s', $namespace, $action);
        if (!$this->hooksService->knowsHook($hookName)) {
            throw new BadRequestHttpException('Unknown action');
        }

        // TODO: Extract and complete mapping
        $apiRequest = new Request();
        $apiRequest->query = (object) ($request->query->getIterator()->getArrayCopy());
        $apiRequest->path = $request->getPathInfo();
        $apiRequest->body = $request->getContent();
        $apiRequest->cookies = (object) ($request->cookies->all());

        /** @var Response $apiResponse */
        $apiResponse = $this->hooksService->call($hookName, [$apiRequest]);

        if (isset($apiResponse['arguments'])) {
            $apiResponse = $apiResponse['arguments'];
        }

        // TODO: Extract and complete mapping
        $response = new SymfonyResponse();
        if (isset($apiResponse['statusCode'])) {
            $response->setStatusCode($apiResponse['statusCode']);
        }
        if (isset($apiResponse['body'])) {
            $response->setContent(
                is_string($apiResponse['body']) ? $apiResponse['body'] : json_encode($apiResponse['body'])
            );
        }

        return $response;
    }
}
