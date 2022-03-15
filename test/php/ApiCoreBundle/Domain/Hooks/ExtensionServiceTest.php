<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\HttpClient;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use GuzzleHttp\Promise\Create;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ExtensionServiceTest extends TestCase
{

    private ExtensionService $subject;

    private ContextService $contextService;
    private RequestStack $requestStack;
    private HttpClient $httpClient;

    private array $extensions = [
        "data-source-frontastic-product-list" => [
            "hookType" => "data-source",
            "hookName" => "data-source-frontastic-product-list",
            "dataSourceIdentifier" => "frontastic/product-list"
        ],
        "dynamic-page-handler" => [
            "hookType" => "dynamic-page-handler",
            "hookName" => "dynamic-page-handler"
        ],
        "action-account-login" => [
            "hookType" => "action",
            "hookName" => "action-account-login",
            "actionNamespace" => "account",
            "actionIdentifier" => "login"
        ]
    ];

    protected function setUp()
    {
        $this->contextService = \Phake::mock(ContextService::class);
        $this->requestStack = \Phake::mock(RequestStack::class);
        $this->httpClient = \Phake::mock(HttpClient::class);

        $this->subject = new ExtensionService(
            $this->contextService,
            $this->requestStack,
            $this->httpClient
        );
    }

    public function testFetchProjectExtensionsWithException()
    {
        $project = "project";
        $path = "http://localhost:8082/hooks/$project";

        $response = new HttpClient\Response();
        $response->status = 500;
        $response->body = "Server error";

        \Phake::when($this->httpClient)->get($path)->thenReturn($response);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Fetching available extensions failed. Error: Server error');

        $this->subject->fetchProjectExtensions($project);
    }

    public function testFetchProjectExtensionsWithExceptionSuccess()
    {
        $project = "project";
        $path = "http://localhost:8082/hooks/$project";


        $response = new HttpClient\Response();
        $response->status = 200;
        $response->body = json_encode($this->extensions);

        \Phake::when($this->httpClient)->get($path)->thenReturn($response);

        $result = $this->subject->fetchProjectExtensions($project);

        $this->assertEquals($this->extensions, $result);
    }

    public function testGetExtensionsCached()
    {
        $this->injectSubjectWithExtensions($this->extensions);

        // Test the method
        $result = $this->subject->getExtensions();

        $this->assertSame($this->extensions, $result);
    }

    public function testGetExtensionsNotCached()
    {
        $mockedSubject = $this->partiallyMockedSubject();
        $this->mockProjectIdentifier();

        \Phake::when($mockedSubject)->fetchProjectExtensions->thenReturn($this->extensions);
        $result = $mockedSubject->getExtensions();

        $this->assertSame($this->extensions, $result);
    }

    public function testHasExtensionTrue()
    {
        $this->injectSubjectWithExtensions($this->extensions);

        $response = $this->subject->hasExtension("data-source-frontastic-product-list");

        $this->assertTrue($response);
    }

    public function testHasExtensionFalse()
    {
        $this->injectSubjectWithExtensions($this->extensions);

        $response = $this->subject->hasExtension("something-random-non-existent");

        $this->assertFalse($response);
    }

    public function testHasDynamicPageHandlerTrue()
    {
        $mockedSubject = $this->partiallyMockedSubject();
        \Phake::when($mockedSubject)->hasExtension('dynamic-page-handler')->thenReturn(true);

        $response = $mockedSubject->hasDynamicPageHandler();

        $this->assertTrue($response);
    }

    public function testHasDynamicPageHandlerFalse()
    {
        $mockedSubject = $this->partiallyMockedSubject();
        \Phake::when($mockedSubject)->hasExtension('dynamic-page-handler')->thenReturn(false);

        $response = $mockedSubject->hasDynamicPageHandler();

        $this->assertFalse($response);
    }

    public function testHasActionTrue()
    {
        $mockedSubject = $this->partiallyMockedSubject();
        \Phake::when($mockedSubject)->hasExtension('action-namespace-action')->thenReturn(true);

        $response = $mockedSubject->hasAction("namespace", "action");

        $this->assertTrue($response);
    }

    public function testHasActionFalse()
    {
        $mockedSubject = $this->partiallyMockedSubject();
        \Phake::when($mockedSubject)->hasExtension('action-namespace-action')->thenReturn(false);

        $response = $mockedSubject->hasAction("namespace", "action");

        $this->assertFalse($response);
    }

    public function testCallDataSourceNoExtension()
    {
        $extensionName = "random-extension-name-that-does-not-exist";
        $arguments = ["arg1", "arg2"];

        $this->injectSubjectWithExtensions($this->extensions);

        $response = $this->subject->callDataSource($extensionName, $arguments);
        $responseData = $response->wait();

        $this->assertNotNull($responseData);
        $this->assertEquals(
            '{"ok":false,"message":"The requested extension \"random-extension-name-that-does-not-exist\" was not found."}',
            $responseData
        );
    }

    public function testCallDataSource()
    {
        $extensionName = "data-source-frontastic-product-list";
        $arguments = ["arg1", "arg2"];

        $this->injectSubjectWithExtensions($this->extensions);
        $this->mockProjectIdentifier();

        $request = new Request(
            [], [], ['_frontastic_request_id' => '1']
        );
        \Phake::when($this->requestStack)->getCurrentRequest->thenReturn($request);

        $response = new HttpClient\Response();
        $response->status = 200;
        $response->body = "AsyncResponse";
        \Phake::when($this->httpClient)->postAsync->thenReturn(
            Create::promiseFor($response)
        );

        $result = $this->subject->callDataSource($extensionName, $arguments);
        $responseData = $result->wait();

        $this->assertNotNull($responseData);
        $this->assertEquals("AsyncResponse", $responseData);
    }

    public function testCallDataSourceResponseError()
    {
        $extensionName = "data-source-frontastic-product-list";
        $arguments = ["arg1", "arg2"];

        $this->injectSubjectWithExtensions($this->extensions);
        $this->mockProjectIdentifier();

        $request = new Request(
            [], [], ['_frontastic_request_id' => '1']
        );
        \Phake::when($this->requestStack)->getCurrentRequest->thenReturn($request);

        $response = new HttpClient\Response();
        $response->status = 500;
        $response->body = "Server error";
        \Phake::when($this->httpClient)->postAsync->thenReturn(
            Create::promiseFor($response)
        );

        $this->expectExceptionMessage("Calling extension data-source-frontastic-product-list failed. Error: Server error");

        $this->subject->callDataSource($extensionName, $arguments)
            ->wait();
    }

    public function testCallDynamicPageHandler() {
        $arguments = ["arg1", "arg2"];

        $this->injectSubjectWithExtensions($this->extensions);
        $this->mockProjectIdentifier();

        $request = new Request(
            [], [], ['_frontastic_request_id' => '1']
        );
        \Phake::when($this->requestStack)->getCurrentRequest->thenReturn($request);

        $response = new HttpClient\Response();
        $response->status = 200;
        $response->body = "{\"response\": \"hello world\"}";
        \Phake::when($this->httpClient)->postAsync->thenReturn(
            Create::promiseFor($response)
        );

        $result = $this->subject->callDynamicPageHandler($arguments);

        $this->assertEquals('hello world', $result->response);
    }

    public function testCallAction() {
        $arguments = ["arg1", "arg2"];

        $this->injectSubjectWithExtensions($this->extensions);
        $this->mockProjectIdentifier();

        $request = new Request(
            [], [], ['_frontastic_request_id' => '1']
        );
        \Phake::when($this->requestStack)->getCurrentRequest->thenReturn($request);

        $response = new HttpClient\Response();
        $response->status = 200;
        $response->body = "{\"response\": \"hello world\"}";
        \Phake::when($this->httpClient)->postAsync->thenReturn(
            Create::promiseFor($response)
        );

        $result = $this->subject->callAction("account", "login", $arguments);

        $this->assertEquals('hello world', $result->response);
    }

    private function injectSubjectWithExtensions(array $extensions)
    {
        //Hacky way to manipulate a private property
        $reflection = new \ReflectionClass($this->subject);
        $reflectionExtensions = $reflection->getProperty("extensions");
        $reflectionExtensions->setAccessible(true);
        $reflectionExtensions->setValue($this->subject, $extensions);
    }

    private function partiallyMockedSubject(): \Phake_IMock
    {
        return \Phake::partialMock(
            ExtensionService::class,
            $this->contextService,
            $this->requestStack,
            $this->httpClient
        );
    }

    private function mockProjectIdentifier()
    {

        $context = new Context([
            "project" => new Project([
                "customer" => "test",
                "projectId" => "project"
            ])
        ]);

        \Phake::when($this->contextService)->createContextFromRequest->thenReturn($context);
    }
}
