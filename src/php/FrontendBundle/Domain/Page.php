<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
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
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var Node
     * @required
     */
    public $node;

    /**
     * @var string
     */
    public $layoutId;

    /**
     * @var Region[]
     * @required
     */
    public $regions = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;

    /**
     * @var bool
     * @required
     */
    public $isDeleted = false;

    /**
     * @var string
     * @required
     */
    public $state;

    /**
     * This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is
     * still the same. It can ensure to maintain the time but the timezone may change which produces a different time
     * point.
     *
     * @var ?int
     */
    public $scheduledFromTimestamp;

    /**
     * This is a UNIX timestamp since doctrine can not persist a \DateTime-object to MySQL and ensure the time point is
     * still the same. It can ensure to maintain the time but the timezone may change which produces a different time
     * point.
     *
     * @var ?int
     */
    public $scheduledToTimestamp;

    /**
     * @var ?int
     */
    public $nodesPagesOfTypeSortIndex = null;

    /**
     * A FECL criterion which can control when this page will be rendered if it is in the scheduled state.
     * @var string
     */
    public $scheduleCriterion = '';
}
