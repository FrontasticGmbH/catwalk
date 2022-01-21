<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Context information delivered to the delivery layer.
 *
 * In contrast to the regular Context, this does not include sensitive configuration data.
 */
class FrontendContext extends DataObject
{

    /**
     * All available locales in form `<language>_<territory>` or just `<territory>`.
     *
     * @var string[]
     */
    public array $locales = [];

    /**
     * Locale to fall back to when no fitting locale could be determined.
     *
     * @var string
     */
    public string $defaultLocale;

    /**
     * One of "development", "staging" or "production".
     *
     * @var string
     */
    public string $environment = 'production';
}
