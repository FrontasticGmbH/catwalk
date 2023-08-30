<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ContentSecurityPolicyTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|ResponseEvent */
    private $event;

    private ContentSecurityPolicy $contentSecurityPolicy;

    /** @var \PHPUnit\Framework\MockObject\MockObject|ResponseHeaderBag */
    private $responseHeaders;

    private Project $project;

    public function setUp(): void
    {
        $this->project = new Project();
        $mockResponse = new stdClass;
        $this->responseHeaders = $this->getMockBuilder(ResponseHeaderBag::class)->getMock();
        $mockResponse->headers = $this->responseHeaders;
        $this->event = $this->getMockBuilder(ResponseEvent::class)->disableOriginalConstructor()->getMock();
        $this->event->expects($this->once())->method('getResponse')->willReturn($mockResponse);

        $this->contentSecurityPolicy = new ContentSecurityPolicy($this->project);
    }

    public function testDefaultConfig()
    {
        /** @noinspection PhpParamsInspection */
        $this->responseHeaders->expects($this->once())->method('set')->with(
            'Content-Security-Policy',
            strtr(
                <<<CSP
default-src 'self';
script-src 'self' 'unsafe-inline' 'unsafe-eval' https://ssl.google-analytics.com https://connect.facebook.net;
img-src 'self' data: *;
style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
font-src 'self' https://themes.googleusercontent.com;
frame-src 'self' https://www.facebook.com https://s-static.ak.facebook.com;
frame-ancestors 'self' https://*.frontastic.io frontastic.io.local;
object-src 'self';
connect-src 'self' ws: wss:
CSP,
                "\n",
                ' '
            )
        );
        $this->contentSecurityPolicy->onKernelResponse($this->event);
    }

    public function testEmptyProjectConfig()
    {
        /** @noinspection PhpParamsInspection */
        $this->responseHeaders->expects($this->once())->method('set')->with(
            'Content-Security-Policy',
            strtr(
                <<<CSP
default-src 'self';
script-src 'self' 'unsafe-inline' 'unsafe-eval';
img-src 'self' data: *;
style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
font-src 'self' https://themes.googleusercontent.com;
frame-src 'self';
frame-ancestors 'self' https://*.frontastic.io frontastic.io.local;
object-src 'self';
connect-src 'self' ws: wss:
CSP,
                "\n",
                ' '
            )
        );
        $this->project->configuration['policy'] = [];
        $this->contentSecurityPolicy->onKernelResponse($this->event);
    }

    public function testCustomProjectConfig()
    {
        /** @noinspection PhpParamsInspection */
        $this->responseHeaders->expects($this->once())->method('set')->with(
            'Content-Security-Policy',
            strtr(
                <<<CSP
default-src 'self' https://example.com;
script-src 'self' 'unsafe-inline' 'unsafe-eval';
img-src 'self' data: * https://img.example.com;
style-src 'self' 'unsafe-inline' https://fonts.googleapis.com;
font-src 'self' https://themes.googleusercontent.com;
frame-src 'self';
frame-ancestors 'self' https://*.frontastic.io frontastic.io.local;
object-src 'self';
connect-src 'self' ws: wss:;
new-src new-value
CSP,
                "\n",
                ' '
            )
        );
        $this->project->configuration['policy'] = [
            'default-src' => [
                // This should be deduplicated
                'https://example.com',
                'https://example.com',
                'https://example.com',
            ],
            'script-src' => [
                // This should also be deduplicated with our baseline configuration
                'self'
            ],
            'img-src' => [
                'https://img.example.com'
            ],
            // In case customers add new keys they should be added
            'new-src' => [
                'new-value'
            ]
        ];
        $this->contentSecurityPolicy->onKernelResponse($this->event);
    }

}
