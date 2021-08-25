<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

/**
 * Response structure as used by Express.js version 4.x + Frontastic additions.
 * {@see https://expressjs.com/en/api.html#res}
 * @type
 */
class Response
{

    /**
     * @var string
     */
    public string $statusCode;

    // public string $statusMessage;

    /**
     * @var string
     */
    public $body;

    // ... more Express.js/Node.js fields

    /**
     * Frontastic session data to be written.
     *
     * @var object
     */
    public object $sessionData;
}
