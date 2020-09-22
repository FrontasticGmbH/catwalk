<?php

namespace Frontastic\Catwalk\KameleoonBundle\Symfony;

use Frontastic\Catwalk\KameleoonBundle\Domain\TrackingService;

class TerminateListener
{
    private $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function onKernelTerminate(PostResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $this->trackingService->flush();
    }
}
