<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Configuration extends DataObject
{
    /**
     * @var boolean
     */
    public $mobile = true;

    /**
     * @var boolean
     */
    public $tablet = true;

    /**
     * @var boolean
     */
    public $desktop = true;

    public function getVisibilityClasses(): string
    {
        return
            (!$this->mobile ? 'hidden-xs ' : '') .
            (!$this->tablet ? 'hidden-sm ' : '') .
            (!$this->desktop ? 'hidden-md hidden-lg ' : '');
    }
}
