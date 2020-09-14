<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Tastic extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $tasticId;

    /**
     * @var string
     * @required
     */
    public $tasticType;

    /**
     * @var string
     * @required
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
     * @var \StdClass
     * @required
     */
    public $configurationSchema;

    /**
     * @var string
     */
    public $environment;

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
}
