<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Generates a UUID for identifying a request. Can be found in logs.
 *
 * We might expose this as an HTTP header in the future.
 * This should probably be moved to the index.php at some point, as soon as that's part of `paas/catwalk`.
 */
class RequestIdListener implements EventSubscriberInterface
{
    private const ATTRIBUTE_KEY = 'frontastic_request_id';

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $request = $event->getRequest();

        if ($request->attributes->has(self::ATTRIBUTE_KEY)) {
            return;
        }

        $uuid4 = Uuid::uuid4();
        $request->attributes->set(self::ATTRIBUTE_KEY, $uuid4->toString());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequest', 999999],
            ],
        ];
    }
}
