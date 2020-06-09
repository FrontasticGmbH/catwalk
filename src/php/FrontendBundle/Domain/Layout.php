<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Layout extends DataObject
{
    /**
     * @var string
     */
    public $layoutId;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $image;

    /**
     * @var string[]
     */
    public $regions = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     */
    public $metaData;
}
