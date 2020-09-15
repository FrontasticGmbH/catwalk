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
     * @required
     */
    public $layoutId;

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var string
     * @required
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
     * @required
     */
    public $regions = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;
}
