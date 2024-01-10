<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class RequestService
{
    const EXCLUDE_HEADERS = ['frontastic-session', 'cookie', 'x-frontastic-access-token', 'frontastic-access-token'];

    private LoggerInterface $logger;
    private string $oldKey;
    private string $newKey;
    private string $payloadKey;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->oldKey = strval(isset($_ENV['session_old_key']) ? $_ENV['session_old_key'] : "");
        $this->newKey = strval(isset($_ENV['session_new_key']) ? $_ENV['session_new_key'] : "");
        $this->payloadKey = strval(isset($_ENV['session_payload_key']) ? $_ENV['session_payload_key'] : "");

        // It's better to just stop the API Hub from working than allowing empty keys
        if (trim($this->newKey) == "" || trim($this->oldKey) == "" || trim($this->payloadKey) == "") {
            throw new \Exception("Invalid session keys");
        }
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
        $apiRequest->method = $request->getMethod();
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
            try {
                $decodedJWT = (array) JWT::decode($sessionData, new Key($this->oldKey, 'HS256'));
            } catch (\Exception $e) {
                $decodedJWT = (array) JWT::decode($sessionData, new Key($this->newKey, 'HS256'));
            }

            if (isset($decodedJWT['nonce']) && isset($decodedJWT['payload'])) {
                $decryptedPayload = sodium_crypto_aead_aes256gcm_decrypt(
                    base64_decode($decodedJWT['payload']),
                    '',
                    base64_decode($decodedJWT['nonce']),
                    base64_decode($this->payloadKey)
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

    /**
     * Generates a secure 12 byte nonce
     * This is essentially just a wrapper around
     * random_bytes() to make unit testing easier
     */
    protected function generateNonce(): string
    {
        return random_bytes(12);
    }

    /**
     * Encrypts the given data into a JWT token signed with HS256 algorithm.
     * Optionally encrypts the payload aswell with AES256
     * @param object|array $data The data to be encoded into a JWT
     * @param bool $shouldEncryptPayload True if the data should be AES256 encrypted, false otherwise
     * @return string The data encoded into a JWT token.
     */
    public function encodeJWTData($data, bool $shouldEncryptPayload): string
    {
        $jwtPayload = $data;

        if ($shouldEncryptPayload) {
            $nonce = $this->generateNonce();
            $encryptedCookie = sodium_crypto_aead_aes256gcm_encrypt(
                json_encode($data),
                '',
                $nonce,
                base64_decode($this->payloadKey)
            );
            $jwtPayload = [
                'payload' => base64_encode($encryptedCookie),
                'nonce' => base64_encode($nonce)
            ];
        }

        // JWT::encode requires an array so we need to make sure it's always an array
        if (!is_array($jwtPayload)) {
            $jwtPayload = json_decode(json_encode($jwtPayload), true);

            if (!is_array($jwtPayload)) {
                $jwtPayload = [];
            }
        }

        return JWT::encode($jwtPayload, $this->newKey, 'HS256');
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
