<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ReferrerPolicy
{
    const DEFAULT = 'same-origin';

    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $event->getResponse()->headers->set(
            'Referrer-Policy',
            $this->context->project->configuration['referrerPolicy'] ?? self::DEFAULT
        );
    }
}
