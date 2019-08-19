<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class CacheDirectives
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $responseHeaders = $event->getResponse()->headers;

        $responseHeaders->set('Vary', 'Accept-Encoding, Accept, Accept-Language');
    }
}
