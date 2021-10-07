<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;

class FrontasticJWTSessionService
{
    //TODO: Make this customer specific later
    const SALT = 'A_OIK_+(#@&#U(98as7ydy6AS%D^sW98sa8d)kMNcx_Si)xudyhX*ASD';

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
