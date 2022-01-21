<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Can be used to redirect to a different path on the website.
 *
 * This is, for example, useful to update the URL of a product detail page when the SEO slug of the product changes.
 *
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
     * The format for this map is to assign a string to a corresponding header key, for example:
     * {
     *     "Retry-After": "120"
     * }
     *
     * @var array<string, string>
     */
    public array $additionalResponseHeaders = [];
}
