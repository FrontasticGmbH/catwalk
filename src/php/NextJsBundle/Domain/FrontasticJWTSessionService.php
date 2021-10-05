<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Symfony\Component\HttpFoundation\Request;

class FrontasticJWTSessionService
{
    public function decodeAndValidateToken(Request $sessionData): Array
    {
        var_dump($sessionData->cookies);
        exit();
    }

    private function validateJWTSessionToken($token)
    {
        return 'b';
    }
}
