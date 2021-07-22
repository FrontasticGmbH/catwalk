<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Component\HttpFoundation\Request;

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
     * Can be null during sitemap generation
     *
     * @var ?Page
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
     * @deprecated Retrieve parameters directly from $request instead!
     */
    public $parameters = [];

    /**
     * Can be null during sitemap generation
     */
    public ?Request $request;
}
