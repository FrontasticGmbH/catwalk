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

    /**
     * @var string[] The list of all locales which have the exact same route for this node.
     */
    public $matchingLocales = [];
}
