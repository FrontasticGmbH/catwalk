<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\NextJsBundle\Domain\Configuration as BaseConfiguration;

/**
 * @type
 */
class LayoutElementConfiguration extends BaseConfiguration
{
    /**
     * @var integer
     * @required
     */
    public $size = 12;
}
