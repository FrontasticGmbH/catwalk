<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class App extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $appId;

    /**
     * @var string
     * @required
     */
    public $identifier;

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
}
