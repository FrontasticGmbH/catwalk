<?php

namespace Frontastic\Catwalk\FrontendBundle\Session;

use Symfony\Component\HttpFoundation\Request;

class StatelessSessionHelper
{
    public static function hasSession(Request $request): bool
    {
        return (!$request->attributes->get('_stateless', false) && $request->hasSession());
    }
}
