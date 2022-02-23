<?php

namespace Frontastic\NextJsBundle\Controller;

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

    public function JWTDecodeProvider(): array
    {
        return [
            'invalid JWT' => [
                'This string is not a valid JWT token.',
                []
            ],
            // JWT HS256
            'valid but payload not encrypted' => [
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJmb28iOiJiYXIifQ.K0I67d15XjGIa1GiHzOXdFqMkPALJH_gwzBp7oULyLA',
                ['foo' => 'bar']
            ],
            // JWT HS256, payload AES256 encrypted
            'valid and encrypted' => [
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiMWlPRTBJR1VXV1FsY2xsMlNFQUcxRWNcL0dzVlFaXC9IXC9NXC9WZ0dyVT0iLCJub25jZSI6IjJ5aHB3MVhDdG9pcEdHbEcifQ.OhlZvQqPNKoh3rrIKXikD-h2c0OL0rkrT2ntEnlgzs0',
                ['foo' => 'bar']
            ]
        ];
    }

    public function JWTEncodeProvider(): array
    {
        // everything here: JWT HS256, payload AES256 encrypted with a nonce of 000000000000
        return [
            'simple 1 key 1 value' => [
                ['foo' => 'bar'],
                '',
            ],
            'empty' => [
                [],
                ''
            ],
            'null value valid key' => [
                ['foo' => null],
                ''
            ],
            'null key null value' => [
                [null => null],
                ''
            ],
            'complex nested object' => [
                [
                    'foo' => [
                        'chocolate' => 'bar',
                        'baz' => 'zab'
                    ],
                    1 => 2,
                    3 => 'value'
                ],
                ''
            ]
        ];
    }

    /**
     * @dataProvider JWTDecodeProvider
     */
    public function testDecodeAndValidateJWTSessionToken($data, $expected)
    {
        $result = $this->requestService->decodeAndValidateJWTSessionToken($data);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider JWTEncodeProvider
     */
    public function testEncodeJWTData($data, $expected)
    {   
        $result = $this->requestService->encodeJWTData($data);

        // $this->assertSame($expected, $result);
    }
}
