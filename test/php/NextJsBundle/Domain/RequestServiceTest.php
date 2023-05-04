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

    public function setUp(): void
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

    public function JWTEncodeEncryptedPayloadProvider(): array
    {
        // everything here: JWT HS256, payload AES256 encrypted with a nonce of 000000000000
        return [
            'simple 1 key 1 value' => [
                ['foo' => 'bar'],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVwMHFpMWZNRDgvK3ZPRXYwU291ZW9TOVBlemcycld3PSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9.isJ1p08XMh1p28dnDUxmJcBNAGvOXtpB66Pai0_Sv5g',
            ],
            'empty' => [
                [],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiV081dCtHV0RxSnlRWC8wVVpITGZRVTEzIiwibm9uY2UiOiJNREF3TURBd01EQXdNREF3In0.DKJ1ovCl_WvThJtluWhoFspWUKYYgtWzDVtyPWtNkfE'
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
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJwYXlsb2FkIjoiZUpFellxdmVweFBpMStsTzdaMHhKL0ZaOVVJeXY0dXJGR21QZ3BiUFl4RFQ1b1lwdlhPMTRlZzN5RWt5SnpXYUZ4UjFJWG5kWFJqdHNkdS85Y1dWTmk3aWVwcGRsOWFuUmc9PSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9.HLHknjVtp3GftK_DRek2xVI0YOrCJJ5oCiBJSZ5VUB8'
            ]
        ];
    }

    public function JWTEncodeNotEncryptedPayloadProvider(): array
    {
        // everything here: JWT HS256, payload not encrypted
        return [
            'simple 1 key 1 value' => [
                ['foo' => 'bar'],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJmb28iOiJiYXIifQ.K0I67d15XjGIa1GiHzOXdFqMkPALJH_gwzBp7oULyLA',
            ],
            'empty' => [
                [],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.W10.aMgKiTUjtr2pEGJaNVhPvohqG0fkTrSmYiVYyNm3HQ0'
            ],
            'null value valid key' => [
                ['foo' => null],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJmb28iOm51bGx9._NfEJSA-hoHQjbQ5wMwXqaFMSr23PIKMkDN6bllhU1I'
            ],
            'null key null value' => [
                [null => null],
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyIiOm51bGx9.KwU0EoacI6mv4A31-3p9MlzLZWe0O9FmcWuLWmem2MY'
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
                'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJmb28iOnsiY2hvY29sYXRlIjoiYmFyIiwiYmF6IjoiemFiIn0sIjEiOjIsIjMiOiJ2YWx1ZSJ9.oGnyOibHyAb2D0KoppuUIFa8lVsIbihw8-J2DAUF4Vw'
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
     * @dataProvider JWTEncodeEncryptedPayloadProvider
     */
    public function testEncodeJWTDataWithEncryptedPayload($data, $expected)
    {

        $requestService = $this->getMockClass(
            RequestService::class,
            array('generateNonce')
        );

        $requestService = new $requestService(new NullLogger());
        $requestService->expects($this->once())
            ->method('generateNonce')
            ->will($this->returnValue('000000000000'));

        $result = $requestService->encodeJWTData($data, true);

        $this->assertSame($expected, $result);
    }

    /**
     * @dataProvider JWTEncodeNotEncryptedPayloadProvider
     */
    public function testEncodeJWTDataWithNotEncryptedPayload($data, $expected)
    {
        $result = $this->requestService->encodeJWTData($data, false);

        $this->assertSame($expected, $result);
    }
}
