<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Stream extends DataObject
{
    /**
     * @var string
     */
    public $streamId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $configuration = [];
}
