<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

class DynamicPageRedirectResult extends DataObject implements DynamicPageResult
{
    public string $redirectLocation;

    public int $statusCode = 301;

    /**
     * Allows to override the standard HTTP status message.
     */
    public ?string $statusMessage = null;

    /**
     * Allows to specify additional headers for the redirect.
     */
    public array $additionalResponseHeaders = [];
}
