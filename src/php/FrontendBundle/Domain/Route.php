<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Route extends DataObject
{
    /**
     * @var string
     */
    public $nodeId;

    /**
     * @var string
     */
    public $route;

    /**
     * @var string|null
     */
    public $locale;
}
