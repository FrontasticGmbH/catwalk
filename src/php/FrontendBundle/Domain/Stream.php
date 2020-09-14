<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Stream extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $streamId;

    /**
     * @var string
     * @required
     */
    public $type;

    /**
     * @var string
     * @required
     */
    public $name;

    /**
     * @var array
     * @required
     */
    public $configuration = [];

    /**
     * @var Tastic[]
     * @required
     */
    public $tastics = [];
}
