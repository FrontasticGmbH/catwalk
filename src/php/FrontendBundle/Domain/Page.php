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
}
