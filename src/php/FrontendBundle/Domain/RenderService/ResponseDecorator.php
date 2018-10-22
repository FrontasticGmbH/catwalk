<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\RenderService;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class ResponseDecorator
{
    /**
     * @var bool
     */
    private $timedOut = false;

    public function setTimedOut()
    {
        $this->timedOut = true;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$this->timedOut) {
            return;
        }

        $response = $event->getResponse();
        $response->setStatusCode(503);
    }
}
