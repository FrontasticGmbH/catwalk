<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Tastic;

use Frontastic\Catwalk\FrontendBundle\Domain\Configuration as BaseConfiguration;

/**
 * @type
 */
class Configuration extends BaseConfiguration
{
    public function __get($name)
    {
        return null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
}
