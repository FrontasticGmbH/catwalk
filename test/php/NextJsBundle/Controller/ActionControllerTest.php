<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Request;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Response;
use Frontastic\Catwalk\NextJsBundle\Domain\ContextCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActionControllerTest extends TestCase
{

    private ActionController $subject;

    /**
     * @var ExtensionService|\Phake_IMock
     */
    private ExtensionService $extensionService;

    /**
     * @var RequestService|\Phake_IMock
     */
    private RequestService $requestService;

    /**
     * @var FromFrontasticReactMapper|\Phake_IMock
     */
    private FromFrontasticReactMapper $fromFrontasticReactMapper;

    /**
     * @var ContextCompletionService|\Phake_IMock
     */
    private ContextCompletionService $contextCompletionService;

    protected function setUp(): void
    {
        $this->extensionService = \Phake::mock(ExtensionService::class);
        $this->requestService = \Phake::mock(RequestService::class);
        $this->fromFrontasticReactMapper = \Phake::mock(FromFrontasticReactMapper::class);
        $this->contextCompletionService = \Phake::mock(ContextCompletionService::class);

        $this->subject = new ActionController(
            $this->extensionService,
            $this->requestService,
            $this->fromFrontasticReactMapper,
            $this->contextCompletionService,
            false
        );
    }

    public function testIndexActionHookNotExists()
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

    public function indexActionSuccessJwtDataProvider()
    {
        return [
            [null, false, null],
            [new \stdClass(), false, 'dummyPlainJWT'],
            [null, true, null],
            [new \stdClass(), true, 'dummyEncryptedJWT']
        ];
    }

    /**
     * @dataProvider indexActionSuccessJwtDataProvider
     */
    public function testIndexActionSuccessJwt($sessionData, $isProduction, $expected)
    {
        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);
        \Phake::when($inputContext)->isProduction()->thenReturn($isProduction);

        $requestMock = \Phake::mock(Request::class);
        \Phake::when($this->requestService)->createApiRequest($inputSymfonyRequest)->thenReturn($requestMock);

        $actionContext = new \Frontastic\Catwalk\NextJsBundle\Domain\Api\Context();
        \Phake::when($this->fromFrontasticReactMapper)->map($inputContext)->thenReturn($actionContext);

        \Phake::when($this->extensionService)->hasAction($inputNamespace, $inputAction)->thenReturn(true);

        $apiResponse = new Response();
        $apiResponse->ok = true;
        $apiResponse->body = "Response Body";
        $apiResponse->statusCode = 200;
        $apiResponse->sessionData = $sessionData;

        \Phake::when($this->requestService)
            ->encodeJWTData($apiResponse->sessionData, false)
            ->thenReturn("dummyPlainJWT");

        \Phake::when($this->requestService)
            ->encodeJWTData($apiResponse->sessionData, true)
            ->thenReturn("dummyEncryptedJWT");

        \Phake::when($this->requestService)
            ->encodeJWTData(null)
            ->thenReturn(null);

        \Phake::when($this->extensionService)->callAction->thenReturn($apiResponse);

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $this->assertNotNull($result);
        $this->assertEquals($apiResponse->body, $result->getContent());
        $this->assertEquals($apiResponse->statusCode, $result->getStatusCode());

        $this->assertEquals($expected, $result->headers->get('frontastic-session'));
    }

    public function testIndexActionSuccess500()
    {
        $apiResponse = new Response();
        $apiResponse->ok = false;
        $apiResponse->sessionData = null;

        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);

        $this->successResponsesCommonGivenWhenThen(
            $inputNamespace,
            $inputAction,
            $inputSymfonyRequest,
            $inputContext,
            $apiResponse
        );

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $this->assertNotNull($result);
        $this->assertEquals(500, $result->getStatusCode());
        $this->assertEquals(json_encode((object)$apiResponse), $result->getContent());
    }

    public function testIndexActionSuccess200()
    {
        $apiResponse = new Response();
        $apiResponse->ok = true;
        $apiResponse->sessionData = null;

        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);

        $this->successResponsesCommonGivenWhenThen(
            $inputNamespace,
            $inputAction,
            $inputSymfonyRequest,
            $inputContext,
            $apiResponse
        );

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $this->assertNotNull($result);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals(
            'Data returned from hook did not have statusCode or body fields',
            $result->headers->get('X-Extension-Error')
        );
    }

    public function testIndexActionSuccess201()
    {
        $apiResponse = new Response();
        $apiResponse->ok = true;
        $apiResponse->statusCode = 201;
        $apiResponse->body = 'TheBody';
        $apiResponse->sessionData = null;

        $inputNamespace = 'namespace';
        $inputAction = 'action';
        $inputSymfonyRequest = \Phake::mock(SymfonyRequest::class);
        $inputContext = \Phake::mock(Context::class);

        $this->successResponsesCommonGivenWhenThen(
            $inputNamespace,
            $inputAction,
            $inputSymfonyRequest,
            $inputContext,
            $apiResponse
        );

        // Call the subject method
        $result = $this->subject->indexAction($inputNamespace, $inputAction, $inputSymfonyRequest, $inputContext);

        $this->assertNotNull($result);
        $this->assertEquals($apiResponse->body, $result->getContent());
        $this->assertEquals($apiResponse->statusCode, $result->getStatusCode());
    }

    private function successResponsesCommonGivenWhenThen(
        $inputNamespace,
        $inputAction,
        $inputSymfonyRequest,
        $inputContext,
        $apiResponse
    ) {

        $requestMock = \Phake::mock(Request::class);
        \Phake::when($this->requestService)->createApiRequest($inputSymfonyRequest)->thenReturn($requestMock);

        $actionContext = new \Frontastic\Catwalk\NextJsBundle\Domain\Api\Context();
        \Phake::when($this->fromFrontasticReactMapper)->map($inputContext)->thenReturn($actionContext);

        \Phake::when($this->extensionService)->hasAction($inputNamespace, $inputAction)->thenReturn(true);

        \Phake::when($this->extensionService)->callAction->thenReturn($apiResponse);
    }
}
