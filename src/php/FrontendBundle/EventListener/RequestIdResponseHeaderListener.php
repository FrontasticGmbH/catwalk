<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Common\CoreBundle\Domain\Tracing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Outputs the frontastic request id as a http header
 */
class RequestIdResponseHeaderListener implements EventSubscriberInterface
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $requestAttributes = $event->getRequest()->attributes;

        if (!$requestAttributes->has(RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY)) {
            return;
        }

        $event->getResponse()->headers->set(
            'Frontastic-Request-Id',
            $requestAttributes->get(
                RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
            )
        );

        $event->getResponse()->headers->set(
            Tracing::CORRELATION_ID_HEADER_KEY,
            $requestAttributes->get(
                RequestIdListener::REQUEST_ID_ATTRIBUTE_KEY
            )
        );
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
