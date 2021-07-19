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
     * @fixme should we transit page folder ID here instead, because the PageFolder can be requested dedicatedly?
     */
    public $pageFolder;

    /**
     * @var string
     * We don't use this at all, yet
     */
    // public $layoutId;

    /**
     * @var Region[]
     * @required
     */
    public $regions = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     * @required
     */
    // public $metaData;

    /**
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
     * Intentionally left out for now since this is only relevant to API Hub
     */
    // public $scheduledFrom;

    /**
     * Intentionally left out for now since this is only relevant to API Hub
     */
    // public $scheduledTo;

    /**
     * Intentionally left out for now since this is only relevant to API Hub
     *
     * @var ?int
     */
    // public $nodesPagesOfTypeSortIndex = null;

    /**
     * A FECL criterion which can control when this page will be rendered if it is in the scheduled state.
     *
     * Intentionally left out for now since this is only relevant to API Hub
     *
     * @var string
     */
    // public $scheduleCriterion = '';

    /**
     * An experiment ID from a third party system like Kameleoon
     *
     * Intentionally left out for now since this is only relevant to API Hub
     *
     * @var ?string
     */
    // public $scheduledExperiment = null;

}
