<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @replaces \Frontastic\Catwalk\FrontendBundle\Domain\Page
 * @type
 */
class Page extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $pageId;

    /**
     * @removed Sequence is not meaningful to the customer and can be re-added later, if needed.;
     */
    // public $sequence;

    /**
     * @removed can be requested dedicatedly
     * @var PageFolder
     * @required
     * @replaces $node
     */
    // public $pageFolder;

    /**
     * @removed We don't use this at all, yet. Can be exposed later, if supported.
     * @var string
     */
    // public $layoutId;

    /**
     * @replaces $regions
     * @var Section[]
     * @required
     */
    public $sections = [];

    /**
     * @removed MetaData is not relevant to API hub but only studio
     * @var \Frontastic\UserBundle\Domain\MetaData
     * @required
     */
    // public $metaData;

    /**
     * @removed extensions will never be called for deleted data
     * @var bool
     * @required
     */
    // public $isDeleted = false;

    /**
     * @var string
     * @required
     */
    public $state;

    /**
     * @removed only relevant to API Hub, can be re-added later, if needed
     */
    // public $scheduledFrom;

    /**
     * @removed only relevant to API Hub, can be re-added later, if needed
     */
    // public $scheduledTo;

    /**
     * @removed only relevant to API Hub, can be re-added later, if needed
     * @var ?int
     */
    // public $nodesPagesOfTypeSortIndex = null;

    /**
     * A FECL criterion which can control when this page will be rendered if it is in the scheduled state.
     *
     * @removed only relevant to API Hub, can be re-added later, if needed
     * @var string
     */
    // public $scheduleCriterion = '';

    /**
     * An experiment ID from a third party system like Kameleoon
     *
     * @removed only relevant to API Hub, can be re-added later, if needed
     * @var ?string
     */
    // public $scheduledExperiment = null;
}
