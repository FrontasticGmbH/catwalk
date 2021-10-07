<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

class FrontasticJWTSessionService
{
    //TODO: Make this customer specific later
    const SALT = '***REMOVED***';

    public function decodeAndValidateToken(string $sessionData): ?array
    {
        /** @phpstan-ignore-next-line */
        return (array) JWT::decode($sessionData, self::SALT, ['HS256']);
    }

    public function encodeData($cookieData): string
    {
        /** @phpstan-ignore-next-line */
        $token = JWT::encode([$cookieData], self::SALT, 'HS256');
        return $token;
    }
}
