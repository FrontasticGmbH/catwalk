<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * Request structure as used by Express.js version 4.x + Frontastic additions.
 * {@see https://expressjs.com/en/api.html#req}
 */
class Request extends DataObject
{
    /**
     * Will be JSON-decoded on the JS side and hold an object there.
     *
     * @var string
     */
    public string $body;

    public object $cookies;

    public string $hostname;

    // ... More properties as specified by Express.js API

    /**
     * Frontastic session data.
     *
     * @var object
     */
    public object $sessionData;
}
