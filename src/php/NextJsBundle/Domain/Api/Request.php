<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Request as coming in to the Frontastci API hub.
 *
 * The request structure is inspired by Express.js version 4.x and contains additional Frontastic $sessionData.
 *
 * @see https://expressjs.com/en/api.html#req
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
     * @var string
     */
    public string $hostname;

    /**
     * @required
     * @var string
     */
    public string $method;

    /**
     * @required
     * @var string
     */
    public string $path;

    /**
     * @required
     * @var object
     */
    public object $query;

    /**
     * @var string
     */
    public string $clientIp;

    /**
     * @var array<string, string>
     */
    public array $headers;

    /**
     * @var string
     */
    public string $frontasticRequestId;

    // ... More properties as specified by Express.js API

    /**
     * Frontastic session data.
     *
     * @var ?object
     */
    public ?object $sessionData = null;
}
