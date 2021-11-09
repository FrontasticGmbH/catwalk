<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Firebase\JWT\JWT;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    const SALT = 'A_OIK_+(#@&#U(98as7ydy6AS%D^sW98sa8d)kMNcx_Si)xudyhX*ASD';

    /**
     * @param SymfonyRequest $request
     * @return Request
     */
    public function createApiRequest(SymfonyRequest $request): Request
    {
        $apiRequest = new Request();
        $apiRequest->query = (object)($request->query->getIterator()->getArrayCopy());
        $apiRequest->path = $request->getPathInfo();
        $apiRequest->body = $request->getContent();
        $apiRequest->cookies = (object)($request->cookies->all());

        $requestSessionData = null;
        if ($request->cookies->get('frontastic-session')) {
            $requestSessionData = (object)$this->decodeAndValidateJWTSessionToken(
                $request->cookies->get('frontastic-session')
            );
        }
        $apiRequest->sessionData = $requestSessionData;

        return $apiRequest;
    }

    public function decodeAndValidateJWTSessionToken(string $sessionData): ?array
    {
        try {
            return (array) JWT::decode($sessionData, self::SALT, ['HS256']);
        } catch (\Exception $e) {
            throw new \Exception("Invalid JWT token in cookie", 401);
        }
    }

    public function encodeJWTData($cookie): string
    {
        return (string) JWT::encode($cookie, self::SALT, 'HS256');
    }
}
