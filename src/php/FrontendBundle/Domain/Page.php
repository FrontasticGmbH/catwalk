<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Page extends DataObject
{
    /**
     * @var string
     */
    public $pageId;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var Node
     */
    public $node;

    /**
     * @var string
     */
    public $layoutId;

    /**
     * @var Region[]
     */
    public $regions = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isDeleted = false;

    /**
     * @var string
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
