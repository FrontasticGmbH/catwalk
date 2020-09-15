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
     * @required
     */
    public $nodeId;

    /**
     * @var string
     * @required
     */
    public $route;

    /**
     * @var ?string
     */
    public $locale;
}
