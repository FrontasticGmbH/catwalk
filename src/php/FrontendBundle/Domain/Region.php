<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Region extends DataObject
{
    /**
     * @var string
     */
    public $regionId;

    /**
     * @var Region\Configuration
     */
    public $configuration;

    /**
     * @var Cell[]
     */
    public $elements = [];

    /**
     * @var Cell[]
     *
     * @deprecated Will be removed, use $elements instead.
     */
    public $cells = [];
}
