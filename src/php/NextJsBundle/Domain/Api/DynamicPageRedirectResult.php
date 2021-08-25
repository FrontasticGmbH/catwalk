<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class DynamicPageRedirectResult extends DataObject implements DynamicPageResult
{
    public string $redirectLocation;

    public int $statusCode = 301;

    /**
     * Allows to override the standard HTTP status message.
     *
     * @var string
     */
    public ?string $statusMessage = null;

    /**
     * Allows to specify additional headers for the redirect.
     *
     * @var array<string, string>
     */
    public array $additionalResponseHeaders = [];
}
