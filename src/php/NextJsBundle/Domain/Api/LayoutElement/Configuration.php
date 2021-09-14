<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\LayoutElement;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\Configuration as BaseConfiguration;

/**
 * @type
 */
class Configuration extends BaseConfiguration
{
    /**
     * @var integer
     * @required
     */
    public $size = 12;
}
