<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

class Tastic extends DataObject
{
    /**
     * @var string
     */
    public $tasticId;

    /**
     * @var string
     */
    public $tasticType;

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
     * @var \StdClass
     */
    public $configurationSchema;

    /**
     * @var string
     */
    public $environment;

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
