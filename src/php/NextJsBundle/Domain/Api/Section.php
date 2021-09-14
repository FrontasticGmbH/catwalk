<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\FrontendBundle\Domain\Region
 * @type
 */
class Section extends DataObject
{
    /**
     * @replaces $regionId
     * @var string
     * @required
     */
    public $sectionId;

    /**
     * @removed Not in use, yet, so we remove it for now.
     * @var Frontastic\Catwalk\FrontendBundle\Domain\Region\Configuration
     * @required
     */
    // public $configuration;

    /**
     * @deprecated Migrating to $layoutElements
     */
    // public $elements = [];

    /**
     * @replaces $cells
     * @var LayoutElement[]
     */
    public $layoutElements = [];
}
