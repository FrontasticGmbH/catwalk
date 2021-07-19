<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * Stripped down version of {@link \Frontastic\Catwalk\FrontendBundle\Domain\Page}
 */
class Page extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $pageId;

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var PageFolder
     * @required
     * @replaces $node
     * @fixme should we transit page folder ID here instead, because the PageFolder can be requested dedicatedly?
     */
    public $pageFolder;

    /**
     * @removed We don't use this at all, yet. Can be exposed later, if supported.
     * @var string
     */
    // public $layoutId;

    /**
     * @var Region[]
     * @required
     */
    public $regions = [];

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
