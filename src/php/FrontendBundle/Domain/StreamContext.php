<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class StreamContext extends DataObject
{
    /**
     * @var Node
     */
    public $node;

    /**
     * @var Page
     */
    public $page;

    /**
     * @var Context
     */
    public $context;

    /**
     * @var Tastic[]
     */
    public $usingTastics = [];

    /**
     * @var array
     */
    public $parameters = [];
}
