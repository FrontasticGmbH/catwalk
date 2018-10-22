<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Tastic extends DataObject
{
    /**
     * @var string
     */
    public $tasticId;

    /**
     * @var string
     */
    public $tasticType;

    /**
     * @var Tastic\Configuration
     */
    public $configuration;
}
