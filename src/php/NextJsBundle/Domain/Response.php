<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

/**
 * Response structure as used by Express.js version 4.x + Frontastic additions.
 * {@see https://expressjs.com/en/api.html#res}
 */
class Response
{
    public string $statusCode;

    public string $statusMessage;

    // ... more Express.js/Node.js fields

    /**
     * Frontastic session data to be written.
     *
     * @var object
     */
    public object $sessionData;
}
