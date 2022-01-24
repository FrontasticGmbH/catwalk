<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

/**
 * Response as to be returned by an "action" extension.
 *
 * The response structure is inspired by Express.js version 4.x + Frontastic sessionData.
 * IMPORTANT: To retain session information you need to return the session that comes in through sessionData in a
 * request in the response of the action.
 *
 * @see https://expressjs.com/en/api.html#res
 * @todo Automatic retaining of session
 * @type
 */
class Response
{

    /**
     * @var int
     * @required
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
     * @var ?object
     */
    public object $sessionData;
}
