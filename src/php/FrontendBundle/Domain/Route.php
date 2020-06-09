<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
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
     * @var ?string
     */
    public $locale;
}
