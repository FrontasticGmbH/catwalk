<?php

namespace Frontastic\NextJsBundle\Controller;

use Firebase\JWT\JWT;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\Request;


class RequestServiceTest extends TestCase
{
    private RequestService $requestService;

    private LoggerInterface $logger;

    public function setUp()
    {
        $this->logger = new NullLogger();

        $this->requestService = new RequestService($this->logger);
    }

    public function testCreateApiRequest()
    {
        $query = [
            'path' => '/no/node/found',
            'locale' => 'en_US',
        ];
        $attributes = [
            '_frontastic_request_id' => 'frontasticRequestIdContent'
        ];
        $cookies = [
            'dummyCookie' => 'dummyCookieContent'
        ];
        $server = [
            'REMOTE_ADDR' => '127.0.0.1',
            'SERVER_NAME' => 'localhost',
            'REQUEST_URI' => '/no/node/found'
        ];
        $body = 'dummycontent';

        $request = new Request($query, [], $attributes, $cookies, [], $server, $body);

        $result = $this->requestService->createApiRequest($request);

        $this->assertEquals($query['path'], $result->path);
        $this->assertEquals($query['locale'], $result->query->locale);
        $this->assertEquals($attributes['_frontastic_request_id'], $result->frontasticRequestId);
        $this->assertArrayNotHasKey('cookie', $result->headers);
        $this->assertEquals($server['REMOTE_ADDR'], $result->clientIp);
        $this->assertEquals($server['SERVER_NAME'], $result->hostname);
        $this->assertEquals($body, $result->body);
    }

    public function testDecodeAndValidateJWTSessionTokenWithInvalidJWT()
    {
        $data = 'This string is not a valid JWT token.';

        $result = $this->requestService->decodeAndValidateJWTSessionToken($data);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testDecodeAndValidateJWTSessionTokenWithUnencryptedPayload()
    {
        $payload = ['foo' => 'bar'];

        $data = JWT::encode($payload, RequestService::SALT, 'HS256');

        $result = $this->requestService->decodeAndValidateJWTSessionToken($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('foo', $result);
        $this->assertEquals($result['foo'], $payload['foo']);
    }

    public function testDecodeAndValidateJWTSessionTokenWithEncryptedPayload()
    {
        $payload = ['foo' => 'bar'];

        $data = $this->requestService->encodeJWTData($payload);

        $result = $this->requestService->decodeAndValidateJWTSessionToken($data);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('foo', $result);
        $this->assertEquals($result['foo'], $payload['foo']);
    }
}
