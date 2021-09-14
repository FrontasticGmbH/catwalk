<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Tastic extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $tasticId;

    /**
     * @var string
     * @required
     */
    public $tasticType;

    /**
     * @var TasticConfiguration
     * @required
     */
    public $configuration;
}
