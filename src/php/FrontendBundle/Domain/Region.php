<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Region extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $regionId;

    /**
     * @var Region\Configuration
     * @required
     */
    public $configuration;

    /**
     * @var Cell[]
     * @required
     */
    public $elements = [];

    /**
     * @var Cell[]
     *
     * @deprecated Will be removed, use $elements instead.
     */
    public $cells = [];
}
