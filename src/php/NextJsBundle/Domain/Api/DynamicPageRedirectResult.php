<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class DynamicPageRedirectResult extends DataObject implements DynamicPageResult
{
    /**
     * @required
     * @var string
     */
    public string $redirectLocation;

    /**
     * @var int
     */
    public int $statusCode = 301;

    /**
     * Allows to override the standard HTTP status message.
     *
     * @var string
     */
    public ?string $statusMessage = null;

    /**
     * Allows specifying additional headers for the redirect.
     *
     * @var array<string, string>
     */
    public array $additionalResponseHeaders = [];
}
