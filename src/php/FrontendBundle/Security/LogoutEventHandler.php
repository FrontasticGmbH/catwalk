<?php

namespace Frontastic\Catwalk\FrontendBundle\Security;

use Frontastic\Common\AccountApiBundle\Domain\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventHandler
{
    public function onSecurityLogout(LogoutEvent $event): void
    {
        $event->setResponse(
            new JsonResponse(new Session([
                'loggedIn' => false,
            ]))
        );
    }
}
