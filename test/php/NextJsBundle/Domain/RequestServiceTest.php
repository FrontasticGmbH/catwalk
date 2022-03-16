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
            ],
            'failing aes256 decryption' => [
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVwMHFpMWZNRDhcLyt2T0V2MFNvdWVvUzlQZXpnMnJXdz0iLCJub25jZSI6IlRTZ25WWktRVkd1RFp2QWYifQ.ndbNXh_C7da54Riw80oXtxsroL_SRye0WbXKFWRrvCc',
                [
                    "payload" =>  "eJEzYqvep0qi1fMD8/+vOEv0SoueoS9Pezg2rWw=",
                    "nonce" =>  "TSgnVZKQVGuDZvAf"
                ]
            ]
        ];
    }

    public function JWTEncodeProvider(): array
    {
        // everything here: JWT HS256, payload AES256 encrypted with a nonce of 000000000000
        return [
            'simple 1 key 1 value' => [
                ['foo' => 'bar'],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVwMHFpMWZNRDhcLyt2T0V2MFNvdWVvUzlQZXpnMnJXdz0iLCJub25jZSI6Ik1EQXdNREF3TURBd01EQXcifQ.QttFQsH_jeS_sugnJB-mNplG4st0e-SXc3Eirruo1PM',
            ],
            'empty' => [
                [],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiV081dCtHV0RxSnlRWFwvMFVaSExmUVUxMyIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9.8tTqmMQivj_KvLHxsp9QQrrkZh3fI-z8lEZLALYk57k'
            ],
            'null value valid key' => [
                ['foo' => null],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVwd2ExMk8xY1BvVWgxZlUwa2R5TTBnMkJwbkxRelE9PSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9.Kx0aZyQWkWTh4sXXjV5dXbA4633nzQG2rfbwNNmDVfo'
            ],
            'null key null value' => [
                [null => null],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpGM042cUo4UVM5czNtYUdSR3hPaHl2Zm5Ham01NXM1UT09Iiwibm9uY2UiOiJNREF3TURBd01EQXdNREF3In0.DK67U5SwMj9lKK9EPBeu5TE8HVr5lpOBc0pq7Jd8m3g'
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
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVweFBpMStsTzdaMHhKXC9GWjlVSXl2NHVyRkdtUGdwYlBZeERUNW9ZcHZYTzE0ZWczeUVreUp6V2FGeFIxSVhuZFhSanRzZHVcLzljV1ZOaTdpZXBwZGw5YW5SZz09Iiwibm9uY2UiOiJNREF3TURBd01EQXdNREF3In0.Qz_A3Uw7cEKlQZ5ETKiTrtH3iizDYP4IQ_-heH4flUM'
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
        $requestService = $this->getMockClass(
            RequestService::class,
            array('generateNonce')
        );

        $requestService = new $requestService(new NullLogger());
        $requestService->expects($this->once())
            ->method('generateNonce')
            ->will($this->returnValue('000000000000'));

        $result = $requestService->encodeJWTData($data);

        $this->assertSame($expected, $result);
    }
}
