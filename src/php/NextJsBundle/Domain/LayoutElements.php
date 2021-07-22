<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\FrontendBundle\Domain\Cell
 */
class LayoutElement extends DataObject
{
    /**
     * @replaces $cellId
     * @var string
     * @required
     */
    public $layoutElementId;

    /**
     * @var Cell\Configuration
     * @required
     * @fixme adjust name
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
