<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Cell extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $cellId;

    /**
     * @var Cell\Configuration
     * @required
     */
    public $configuration;

    /**
     * @var ?\stdClass
     */
    public $customConfiguration;

    /**
     * @var Tastic[]
     * @required
     */
    public $tastics = [];
}
