<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Outputs the frontastic request id as a http header
 */
class RequestIdResponseHeaderListener implements EventSubscriberInterface
{
    private const ATTRIBUTE_KEY = '_frontastic_request_id';

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $requestAttributes = $event->getRequest()->attributes;

        if (!$requestAttributes->has(self::ATTRIBUTE_KEY)) {
            return;
        }

        $event->getResponse()->headers->set('Frontastic-Request-Id', $requestAttributes->get(self::ATTRIBUTE_KEY));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['onKernelResponse', 0],
            ],
        ];
    }
}
