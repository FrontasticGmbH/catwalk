<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

class App extends DataObject
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $identifier;

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
}
