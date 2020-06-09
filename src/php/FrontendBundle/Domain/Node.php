<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Node extends DataObject
{
    /**
     * @var string
     */
    public $nodeId;

    /**
     * @var bool
     */
    public $isMaster = false;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var array
     */
    public $configuration = [];

    /**
     * @var Stream[]
     */
    public $streams = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $path = [];

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var integer
     */
    public $sort = 0;

    /**
     * @var Node[]
     */
    public $children = [];

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * Optional error string during development
     *
     * @var ?string
     */
    public $error;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
