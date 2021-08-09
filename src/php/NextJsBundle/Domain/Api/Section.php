<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\FrontendBundle\Domain\Region\Configuration as OriginalRegionConfiguration;

use Kore\DataObject\DataObject;

/**
 * @replaces Frontastic\Catwalk\FrontendBundle\Domain\Region
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
     * @var OriginalRegionConfiguration
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
