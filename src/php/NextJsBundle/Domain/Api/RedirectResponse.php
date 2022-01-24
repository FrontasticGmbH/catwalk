<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Redirect response
 */
class RedirectResponse extends DataObject
{
    const REASON_LOCALE_MISMATCH = 'locale mismatch';
    const REASON_REDIRECT_EXISTS_FOR_PATH = 'redirect exists for path';

    const TARGET_TYPE_PAGE_FOLDER = 'page folder';
    const TARGET_TYPE_LINK = 'link';

    /**
     * @var string
     */
    public int $statusCode;

    /**
     * One of REASON_* constants
     * @var string
     */
    public string $reason;

    /**
     * One of TARGET_TYPE_* constants
     * @var string
     */
    public string $targetType;

    /**
     * The target url or path
     *
     * @var string
     */
    public string $target;
}
