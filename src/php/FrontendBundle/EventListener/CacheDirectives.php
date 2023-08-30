<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class CacheDirectives
{
    public function onKernelResponse(ResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;

        $responseHeaders->set('Vary', 'Accept-Encoding, Accept, Accept-Language');
    }
}
