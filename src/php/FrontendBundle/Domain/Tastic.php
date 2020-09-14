<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

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
     * @var Tastic\Configuration
     * @required
     */
    public $configuration;
}
