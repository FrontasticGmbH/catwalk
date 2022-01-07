<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Firebase\JWT\JWT;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    const SALT = 'A_OIK_+(#@&#U(98as7ydy6AS%D^sW98sa8d)kMNcx_Si)xudyhX*ASD';

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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
        // We keep this for backwards compatibility the cookie will have to be removed when the header is full in place
        if ($request->cookies->get('frontastic-session')) {
            $requestSessionData = (object)$this->decodeAndValidateJWTSessionToken(
                $request->cookies->get('frontastic-session')
            );
        }

        if ($request->headers->get('frontastic-session')) {
            $requestSessionData = (object)$this->decodeAndValidateJWTSessionToken(
                $request->headers->get('frontastic-session')
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
            $this->logger->error(
                'Error in session handling resetting the session data, tip session can not be null',
                [
                    "message" => $e->getMessage()
                ]
            );
            return [];
        }
    }

    public function encodeJWTData($cookie): string
    {
        return (string) JWT::encode($cookie, self::SALT, 'HS256');
    }
}
