<?php

namespace Frontastic\Catwalk\FrontendBundle\Security;

use Frontastic\Common\AccountApiBundle\Domain\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     * @return void never null
     */
    public function onLogoutSuccess(Request $request)
    {
        return new JsonResponse(new Session([
            'loggedIn' => false,
        ]));
    }
}
