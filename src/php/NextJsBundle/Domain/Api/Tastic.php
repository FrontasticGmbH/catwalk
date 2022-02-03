<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Tastic extends DataObject
{
    /**
     * Unique on the page. Might be used for #href links.
     *
     * @var string
     * @required
     */
    public $tasticId;

    /**
     * Type as defined in the Tastic schema.
     *
     * @var string
     * @required
     */
    public $tasticType;

    /**
     * @var Tastic\Configuration
     * @required
     */
    public $configuration;
}
