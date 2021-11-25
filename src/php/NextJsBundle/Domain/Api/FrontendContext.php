<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

class FrontendContext extends DataObject
{

    /**
     * @var string[]
     */
    public array $locales = [];

    /**
     * @var string
     */
    public string $defaultLocale;

    /**
     * Result of {@link Frontastic\Catwalk\ApiCoreBundle\Domain\Context.applicationEnvironment()}
     *
     * @var string
     */
    public string $environment = 'production';
}
