<?php

namespace Frontastic\Catwalk\KameleoonBundle\Symfony;

use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Frontastic\Catwalk\KameleoonBundle\Domain\TrackingService;

class TerminateListener
{
    private $trackingService;

    public function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $this->trackingService->flush();
    }
}
