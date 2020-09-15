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
     * @required
     */
    public $mobile = true;

    /**
     * @var boolean
     * @required
     */
    public $tablet = true;

    /**
     * @var boolean
     * @required
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
