<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\NextJsBundle\Domain\FrontasticJWTSessionService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    private FrontasticJWTSessionService $frontasticJWTSessionService;

    public function __construct(frontasticJWTSessionService $frontasticJWTSessionService)
    {
        $this->frontasticJWTSessionService = $frontasticJWTSessionService;
    }

    /**
     * @param SymfonyRequest $request
     * @return Request
     */
    public function createApiRequest(SymfonyRequest $request, $sessionData = null): Request
    {

        $content = json_decode($request->getContent(), true);

        if ($sessionData) {
            $content['sessionData'] = $sessionData;
        }

        $apiRequest = new Request();
        $apiRequest->query = (object)($request->query->getIterator()->getArrayCopy());
        $apiRequest->path = $request->getPathInfo();
        $apiRequest->body = json_encode($content);
        $apiRequest->cookies = (object)($request->cookies->all());
        $apiRequest->body;

        return $apiRequest;
    }

    public function decodeAndValidateJWTSessionToken(string $sessionData): ?array
    {
        return $this->frontasticJWTSessionService->decodeAndValidateToken($sessionData);
    }

    public function encodeJWTData($cookie): string
    {
        return $this->frontasticJWTSessionService->encodeData($cookie);
    }
}
