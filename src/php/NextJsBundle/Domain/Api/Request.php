<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Request structure as used by Express.js version 4.x + Frontastic additions.
 * {@see https://expressjs.com/en/api.html#req}
 * @type
 */
class Request extends DataObject
{
    /**
     * Will be JSON-decoded on the JS side and hold an object there.
     *
     * @var string
     */
    public string $body;

    /**
     * <cookie-name> -> <cookie-value>
     * @var array<string, string>
     */
    public object $cookies;

    /**
     * @var string
     */
    public string $hostname;

    /**
     * @var string
     */
    public string $method;

    /**
     * @var string
     */
    public string $path;

    /**
     * @var object
     */
    public object $query;

    // ... More properties as specified by Express.js API

    /**
     * Frontastic session data.
     *
     * @var object
     */
    public object $sessionData;
}
