<?php

namespace Frontastic\Catwalk\NextJsBundle\EventListener;

use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class CorsHandlerTest extends TestCase
{
    private CorsHandler $corsHandler;

    public function setUp(): void
    {
        $this->corsHandler = new CorsHandler();
    }

    private function getFakePreflightRequest()
    {
        $request = new Request();

        $request->setMethod('OPTIONS');

        $request->headers->set('Origin', 'https://frontastic.cloud');
        $request->headers->set('Access-Control-Request-Method', 'GET');
        $request->headers->set('Access-Control-Request-Headers', 'Origin, Content-Type, Accept, Cookie, Frontastic-Locale, Frontastic-Path, Frontastic-Session, X-Frontastic-Access-Token, coFE-Custom-Configuration, Commercetools-Frontend-Extension-Version');

        return $request;
    }

    private function getFakeRequest($method = 'GET')
    {
        $request = new Request();

        $request->setMethod($method);

        $request->headers->set('Origin', 'https://frontastic.cloud');

        return $request;
    }

    public function testOnKernelRequestPreflightRequestSetsResponse()
    {
        $request = $this->getFakePreflightRequest();

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $this->assertNotNull($event->getResponse());
    }

    public function testOnKernelRequestSetsCorrectHeaders()
    {
        $request = $this->getFakePreflightRequest();

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $headers = $event->getResponse()->headers;

        $this->assertNotNull($event->getResponse());
        $this->assertEquals(
            $request->headers->get('Origin'),
            $headers->get('Access-Control-Allow-Origin')
        );
        $this->assertEquals(
            '*',
            $headers->get('Access-Control-Allow-Methods')
        );
        $this->assertEquals(
            'Origin, Content-Type, Accept, Cookie, Frontastic-Locale, Frontastic-Path, Frontastic-Session, X-Frontastic-Access-Token, coFE-Custom-Configuration, Commercetools-Frontend-Extension-Version',
            $headers->get('Access-Control-Allow-Headers')
        );
        $this->assertEquals(
            'true',
            $headers->get('Access-Control-Allow-Credentials')
        );
    }

    public function testOnKernelRequestPreflightSubRequestDoesntSetResponse()
    {
        $request = $this->getFakePreflightRequest();

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::SUB_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelRequestGetRequestDoesntSetResponse()
    {
        $request = $this->getFakeRequest();

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelResponseSetsCorrectHeaders()
    {
        $request = $this->getFakeRequest();

        $response = new Response();

        $event = new ResponseEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $this->corsHandler->onKernelResponse($event);

        $headers = $event->getResponse()->headers;

        $this->assertNotNull($event->getResponse());
        $this->assertEquals(
            $request->headers->get('Origin'),
            $headers->get('Access-Control-Allow-Origin')
        );
        $this->assertEquals(
            '*',
            $headers->get('Access-Control-Allow-Methods')
        );
        $this->assertEquals(
            'Origin, Content-Type, Accept, Cookie, Frontastic-Locale, Frontastic-Path, Frontastic-Session, X-Frontastic-Access-Token, coFE-Custom-Configuration, Commercetools-Frontend-Extension-Version',
            $headers->get('Access-Control-Allow-Headers')
        );
        $this->assertEquals(
            '*, Authorization, Frontastic-Locale, Frontastic-Path, Frontastic-Session, X-Frontastic-Access-Token, coFE-Custom-Configuration, Commercetools-Frontend-Extension-Version',
            $headers->get('Access-Control-Expose-Headers')
        );
        $this->assertEquals(
            'true',
            $headers->get('Access-Control-Allow-Credentials')
        );
    }

    public function testOnKernelResponseSubRequestDoesntSetHeaders()
    {
        $request = $this->getFakeRequest();

        $response = new Response();

        $event = new ResponseEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::SUB_REQUEST,
            $response
        );

        $this->corsHandler->onKernelResponse($event);

        $headers = $event->getResponse()->headers;

        $this->assertNull($headers->get('Access-Control-Allow-Origin'));
        $this->assertNull($headers->get('Access-Control-Allow-Methods'));
        $this->assertNull($headers->get('Access-Control-Allow-Headers'));
        $this->assertNull($headers->get('Access-Control-Expose-Headers'));
        $this->assertNull($headers->get('Access-Control-Allow-Credentials'));
    }
}
