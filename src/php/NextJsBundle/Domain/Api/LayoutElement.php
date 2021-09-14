<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\FrontendBundle\Domain\Cell
 * @type
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
     * @var LayoutElement\Configuration
     * @required
     */
    public $configuration;

    /**
     * @removed Does not work right now so we don't expose it for now
     * @var ?\stdClass
     */
    // public $customConfiguration;

    /**
     * @var Tastic[]
     * @required
     */
    public $tastics = [];
}
