<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\HttpClient;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class ExtensionServiceTest extends TestCase
{

    private ExtensionService $subject;

    private ContextService $contextService;
    private RequestStack $requestStack;
    private HttpClient $httpClient;

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

        $responseBody = [
            "data-source-frontastic-product-list" => [
                "hookType" => "data-source",
                "hookName" => "data-source-frontastic-product-list",
                "dataSourceIdentifier" => "frontastic/product-list"
            ]
        ];

        $response = new HttpClient\Response();
        $response->status = 200;
        $response->body = json_encode($responseBody);

        \Phake::when($this->httpClient)->get($path)->thenReturn($response);

        $result = $this->subject->fetchProjectExtensions($project);

        $this->assertEquals($responseBody, $result);
    }

    public function testGetExtensionsCached()
    {
        $extensions = [
            "data-source-frontastic-product-list" => [
                "hookType" => "data-source",
                "hookName" => "data-source-frontastic-product-list",
                "dataSourceIdentifier" => "frontastic/product-list"
            ]
        ];

        //Hacky way to manipulate a private property
        $reflection = new \ReflectionClass($this->subject);
        $reflection_extensions = $reflection->getProperty("extensions");
        $reflection_extensions->setAccessible(true);
        $reflection_extensions->setValue($this->subject, $extensions);

        // Test the method
        $result = $this->subject->getExtensions();

        $this->assertSame($extensions, $result);
    }

    public function testGetExtensionsNotCached()
    {
        $mockedSubject = \Phake::partialMock(
            ExtensionService::class,
            $this->contextService,
            $this->requestStack,
            $this->httpClient
        );

        $extensions = [
            "data-source-frontastic-product-list" => [
                "hookType" => "data-source",
                "hookName" => "data-source-frontastic-product-list",
                "dataSourceIdentifier" => "frontastic/product-list"
            ]
        ];

        $this->mockProjectIdentifier();
        \Phake::when($mockedSubject)->fetchProjectExtensions->thenReturn($extensions);
        $result = $mockedSubject->getExtensions();

        $this->assertSame($extensions, $result);
    }

    private function mockProjectIdentifier() {

        $context = new Context([
            "project" => new Project([
                "customer" => "test",
                "projectId" => "project"
            ])
        ]);

        \Phake::when($this->contextService)->createContextFromRequest->thenReturn($context);
    }
}
