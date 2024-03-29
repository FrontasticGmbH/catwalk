<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Kore\DataObject\DataObject;

/**
 * Redirect response
 */
class RedirectResponse extends DataObject
{
    const REASON_LOCALE_MISMATCH = 'locale mismatch';
    const REASON_REDIRECT_EXISTS_FOR_PATH = 'redirect exists for path';
    const REASON_DYNAMIC_PAGE_REDIRECT = 'dynamic page redirect';

    const TARGET_TYPE_PAGE_FOLDER = 'page folder';
    const TARGET_TYPE_LINK = 'link';
    const TARGET_TYPE_UNKNOWN = 'unknown';

    /**
     * @var int
     * @required
     */
    public int $statusCode = 301;

    /**
     * One of REASON_* constants
     * @var string
     * @required
     */
    public string $reason;

    /**
     * One of TARGET_TYPE_* constants
     * @var string
     * @required
     */
    public string $targetType;

    /**
     * The target url or path
     *
     * @var string
     * @required
     */
    public string $target;
}
