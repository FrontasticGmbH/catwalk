<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Cell extends DataObject
{
    /**
     * @var string
     */
    public $cellId;

    /**
     * @var Cell\Configuration
     */
    public $configuration;

    /**
     * @var ?\stdClass
     */
    public $customConfiguration;

    /**
     * @var Tastic[]
     */
    public $tastics = [];
}
