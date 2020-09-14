<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

/**
 * @type
 */
class StreamContext extends DataObject
{
    /**
     * @var Node
     * @required
     */
    public $node;

    /**
     * @var Page
     * @required
     */
    public $page;

    /**
     * @var Context
     * @required
     */
    public $context;

    /**
     * @var Tastic[]
     * @required
     */
    public $usingTastics = [];

    /**
     * Parameters given to the stream in the current request context.
     *
     * @var array
     * @required
     */
    public $parameters = [];
}
