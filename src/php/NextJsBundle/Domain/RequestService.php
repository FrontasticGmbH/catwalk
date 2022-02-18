<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Firebase\JWT\JWT;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    const SALT = 'A_OIK_+(#@&#U(98as7ydy6AS%D^sW98sa8d)kMNcx_Si)xudyhX*ASD';
    const AES256_KEY = '6hrRm4EdwrHmdabeg2VXqcA0LNTzIUaZefxQaSRBo9g=';
    const EXCLUDE_HEADERS = ['frontastic-session', 'cookie', 'x-frontastic-access-token', 'frontastic-access-token'];

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
        $apiRequest->headers = $this->filterOutHeaders($request->headers->all());
        $apiRequest->clientIp = $request->getClientIp();
        $apiRequest->hostname = $request->getHost();
        $apiRequest->frontasticRequestId = $request->attributes->get('_frontastic_request_id');

        $requestSessionData = null;
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
            $decodedJWT = (array) JWT::decode($sessionData, self::SALT, ['HS256']);

            if (isset($decodedJWT['nonce']) && isset($decodedJWT['payload'])) {
                $decryptedPayload = sodium_crypto_aead_aes256gcm_decrypt(
                    base64_decode($decodedJWT['payload']),
                    '',
                    base64_decode($decodedJWT['nonce']),
                    base64_decode(self::AES256_KEY)
                );

                if ($decryptedPayload !== false) {
                    return (array) json_decode($decryptedPayload);
                }
            }

            return $decodedJWT;
        } catch (\Exception $e) {
            $this->logger->error(
                'Error in session handling, resetting the session data',
                [
                    "message" => $e->getMessage()
                ]
            );
            return [];
        }
    }

    public function encodeJWTData($cookie): string
    {   
        $nonce = random_bytes(12);
        $encryptedCookie = sodium_crypto_aead_aes256gcm_encrypt(
            json_encode($cookie), 
            '', 
            $nonce,
            base64_decode(self::AES256_KEY)
        );

        return JWT::encode([
            'payload' => base64_encode($encryptedCookie),
            'nonce' => base64_encode($nonce)
        ], self::SALT, 'HS256');
    }

    /**
     * Filters out sensitive headers specified in BLACKLIST_HEADERS
     *
     * @param array $headers
     * @return array
     */
    private function filterOutHeaders(array $headers): array
    {
        return array_filter(
            $headers,
            fn ($key) => !in_array(strtolower($key), self::EXCLUDE_HEADERS),
            ARRAY_FILTER_USE_KEY
        );
    }
}
