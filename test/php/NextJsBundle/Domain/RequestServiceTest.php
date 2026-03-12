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

        // the secrets used by the RequestService for the tests are set in phpunit.xml
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
                // Use implode to "hide" the test tokens from the Orca Security Scan
                // These tokens are generated for the unit test and can't be used to gain access in any real system.
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJmb28iOiJiYXIifQ',
                        'LDOcyWwEkmDU-47q-C4JWjKbncYDHprMVnuKmddObVY',
                    ]
                ),
                ['foo' => 'bar']
            ],
            // JWT HS256, payload AES256 encrypted
            'valid and encrypted' => [
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiLzNJbFJZanJqV3dRaE8rNnRkUjB2bzlZVU1sK0JVMWJ6aldmZEdzPSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9',
                        't8ClrqPsyBllQFeVrBFJAq2TCWV5eooo_AX2hStFZBs',
                    ]
                ),
                ['foo' => 'bar']
            ],
            'failing aes256 decryption' => [
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiZUpFellxdmVwMHFpMWZNRDgvK3ZPRXYwU291ZW9TOVBlemcycld3PSIsIm5vbmNlIjoiVFNnblZaS1FWR3VEWnZBZiJ9',
                        'TuhDJx0Yd5SvyfO60fpiD0riih0hUwRyGL_qfU9bMO4',
                    ]
                ),
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
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiLzNJbFJZanJqV3dRaE8rNnRkUjB2bzlZVU1sK0JVMWJ6aldmZEdzPSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9',
                        't8ClrqPsyBllQFeVrBFJAq2TCWV5eooo_AX2hStFZBs',
                    ]
                ),
            ],
            'empty' => [
                [],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiM3cyY0xET1o3Q2Z2TlBuT0NhU2srVkRLIiwibm9uY2UiOiJNREF3TURBd01EQXdNREF3In0',
                        'CEeVFsKF8IR3zVHs5WxQEmRd_FvF64298roDT1Iuvnc',
                    ]
                ),
            ],
            'null value valid key' => [
                ['foo' => null],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiLzNJbFJZanJqU0FIaWZIbE5sZm04TWR6d2hjcVJNM2JHTm5uQUE9PSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9',
                        'a1TFrtGQQN48oC9PIv-nebZtVTGcbMSptVORgp_brA0',
                    ]
                ),
            ],
            'null key null value' => [
                [null => null],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiLzNKaEVJbTgyeUlQVVVXUmQ3MHd2TWhZTHk4ejJRcGhydz09Iiwibm9uY2UiOiJNREF3TURBd01EQXdNREF3In0',
                        'NXiCnfIuV7AXw-hh6er1qjeNwb8k1HJm9EE3O5iKEfo',
                    ]
                ),
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
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJwYXlsb2FkIjoiLzNJbFJZanJqVFZRaHZYM3Erc09iMGdTZ1IrdW1xc3FoZFF0ZW5MNlpsbHRjRFE1TS9vRXdOUklML0dNRG5kMEJmZUtqMkV2dG4zV1dBSmNZZlFEc2RReDdjNURZSlhuN0E9PSIsIm5vbmNlIjoiTURBd01EQXdNREF3TURBdyJ9',
                        '9DTdf3LMukcFlNZOmScySnT2tWqLqgTYvMXEOtzzpMY',
                    ]
                ),
            ]
        ];
    }

    public function JWTEncodeNotEncryptedPayloadProvider(): array
    {
        // everything here: JWT HS256, payload not encrypted
        return [
            'simple 1 key 1 value' => [
                ['foo' => 'bar'],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJmb28iOiJiYXIifQ',
                        'LDOcyWwEkmDU-47q-C4JWjKbncYDHprMVnuKmddObVY',
                    ]
                ),
            ],
            'empty' => [
                [],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'W10',
                        '7eoQIaGwq7x-Img1zEpXorbR08JDQzjU-e39MkfWfms',
                    ]
                ),
            ],
            'null value valid key' => [
                ['foo' => null],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJmb28iOm51bGx9',
                        'y-YSALikrqgC0f8rBpQhqlYvT6-o_Y61WFqjI3pKL0s',
                    ]
                ),
            ],
            'null key null value' => [
                [null => null],
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyIiOm51bGx9',
                        'HweP3Xxb0Ve804n0-Mmg-jINy7gdQJdoZ0aJ8SRHZi8',
                    ]
                ),
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
                implode('.',
                    [
                        'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9',
                        'eyJmb28iOnsiY2hvY29sYXRlIjoiYmFyIiwiYmF6IjoiemFiIn0sIjEiOjIsIjMiOiJ2YWx1ZSJ9',
                        'nllgFK-tjX8ETw9igX2D6SyYvcs2ZaYs-aCeQvGEzWc',
                    ]
                ),
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
        $requestService = $this->getMockBuilder(RequestService::class)
            ->onlyMethods(array('generateNonce'))
            ->setConstructorArgs(array(new NullLogger()))
            ->getMock();

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
