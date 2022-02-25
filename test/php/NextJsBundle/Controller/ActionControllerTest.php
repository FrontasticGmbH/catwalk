<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionControllerTest extends TestCase
{

    private ActionController $subject;

    private HooksService $hooksService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $fromFrontasticReactMapper;

    protected function setUp(): void
    {
        $this->hooksService = \Phake::mock(HooksService::class);
        $this->requestService = \Phake::mock(RequestService::class);
        $this->fromFrontasticReactMapper = \Phake::mock(FromFrontasticReactMapper::class);

        $this->subject = new ActionController(
            $this->hooksService,
            $this->requestService,
            $this->fromFrontasticReactMapper,
            false
        );
    }

    public function testIndexAction_fail_hookNotExists()
    {
        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = new Context();

        $requestMock = \Phake::mock(Request::class);
        \Phake::when($this->requestService)->createApiRequest($inputSymfonyRequest)->thenReturn($requestMock);

        $actionContext = new \Frontastic\Catwalk\NextJsBundle\Domain\Api\Context();
        \Phake::when($this->fromFrontasticReactMapper)->map($inputContext)->thenReturn($actionContext);

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage(sprintf(
            'Action "%s" in namespace "%s" is not registered',
            $inputAction,
            $inputNamespace
        ));

        // Call the subject method
        $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);
    }

    public function testIndexAction_success_jwt_dataProvider()
    {
        return [
            [
                function ($that, $apiResponse) {
                    $apiResponse->sessionData = null;
                },
                function ($result) {
                    $this->assertNull($result->headers->get('frontastic-session')); //cleared session
                }
            ],
            [
                function ($that, $apiResponse) {
                    $apiResponse->sessionData = new \stdClass();
                    \Phake::when($that->requestService)->encodeJWTData($apiResponse->sessionData)->thenReturn("dummyJWT");
                },
                function ($result) {
                    $this->assertEquals("dummyJWT", $result->headers->get('frontastic-session')); //set session
                }
            ]
        ];
    }

    /**
     * @dataProvider testIndexAction_success_jwt_dataProvider
     */
    public function testIndexAction_success_jwt($preRunFunc, $extraAssertionsFunc)
    {
        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);

        $requestMock = \Phake::mock(Request::class);
        \Phake::when($this->requestService)->createApiRequest($inputSymfonyRequest)->thenReturn($requestMock);

        $actionContext = new \Frontastic\Catwalk\NextJsBundle\Domain\Api\Context();
        \Phake::when($this->fromFrontasticReactMapper)->map($inputContext)->thenReturn($actionContext);

        \Phake::when($this->hooksService)->isHookRegistered("action-$inputNamespace-$inputAction")->thenReturn(true);

        $apiResponse = new Response();
        $apiResponse->ok = true;
        $apiResponse->body = "Response Body";
        $apiResponse->statusCode = 200;

        \Phake::when($this->hooksService)->call->thenReturn($apiResponse);

        $preRunFunc($this, $apiResponse);

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $this->assertNotNull($result);
        $this->assertEquals($apiResponse->body, $result->getContent());
        $this->assertEquals($apiResponse->statusCode, $result->getStatusCode());

        $extraAssertionsFunc($result);
    }


    public function testIndexAction_success_responses_dataProvider()
    {
        return [
            [
                function () {
                    $apiResponse = new Response();
                    $apiResponse->ok = false;
                    $apiResponse->sessionData = null;

                    return $apiResponse;
                },
                function ($inputApiResponse, JsonResponse $result) {
                    $this->assertNotNull($result);
                    $this->assertEquals(500, $result->getStatusCode());
                    $this->assertEquals(json_encode((object)$inputApiResponse), $result->getContent());
                }
            ],
            [
                function () {
                    $apiResponse = new Response();
                    $apiResponse->ok = true;
                    $apiResponse->sessionData = null;

                    return $apiResponse;
                },
                function ($inputApiResponse, JsonResponse $result) {
                    $this->assertNotNull($result);
                    $this->assertEquals(200, $result->getStatusCode());
                    $this->assertEquals(
                        'Data returned from hook did not have statusCode or body fields',
                        $result->headers->get('X-Extension-Error')
                    );
                }
            ],
            [
                function () {
                    $apiResponse = new Response();
                    $apiResponse->ok = true;
                    $apiResponse->statusCode = 201;
                    $apiResponse->body = 'TheBody';
                    $apiResponse->sessionData = null;

                    return $apiResponse;
                },
                function ($inputApiResponse, JsonResponse $result) {
                    $this->assertNotNull($result);
                    $this->assertEquals($inputApiResponse->body, $result->getContent());
                    $this->assertEquals($inputApiResponse->statusCode, $result->getStatusCode());
                }
            ]

        ];
    }

    /**
     * @dataProvider testIndexAction_success_responses_dataProvider
     */
    public function testIndexAction_success_responses($apiResponseFunc, $assertFunc)
    {
        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);

        $requestMock = \Phake::mock(Request::class);
        \Phake::when($this->requestService)->createApiRequest($inputSymfonyRequest)->thenReturn($requestMock);

        $actionContext = new \Frontastic\Catwalk\NextJsBundle\Domain\Api\Context();
        \Phake::when($this->fromFrontasticReactMapper)->map($inputContext)->thenReturn($actionContext);

        \Phake::when($this->hooksService)->isHookRegistered("action-$inputNamespace-$inputAction")->thenReturn(true);

        $apiResponse = $apiResponseFunc();

        \Phake::when($this->hooksService)->call->thenReturn($apiResponse);

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $assertFunc($apiResponse, $result);
    }
}
