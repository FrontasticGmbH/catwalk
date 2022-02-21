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

    public function setUp()
    {
        $this->corsHandler = new CorsHandler();
    }

    public function testOptionsRequestSetsResponse()
    {
        $request = new Request();
        $request->setMethod('OPTIONS');

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $this->assertNotNull($event->getResponse());
    }

    public function testGetRequestsDoesntSetResponse()
    {
        $request = new Request();
        $request->setMethod('GET');

        $event = new RequestEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->corsHandler->onKernelRequest($event);

        $this->assertNull($event->getResponse());
    }

    public function testOnKernelResponseSetsAccessControlAllowOriginHeader()
    {
        $request = new Request();
        $request->setMethod('GET');
        $origin = 'https://frontastic.cloud';
        $request->headers->add(['Origin' => $origin]);

        $response = new Response();

        $event = new ResponseEvent(
            new HttpKernel(new EventDispatcher(), new ControllerResolver()),
            $request,
            HttpKernelInterface::MASTER_REQUEST,
            $response
        );

        $this->corsHandler->onKernelResponse($event);

        $this->assertEquals(
            $origin,
            $event->getResponse()->headers->get('Access-Control-Allow-Origin')
        );
    }
}
