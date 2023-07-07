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
     * @required
     */
    public $nodeId;

    /**
     * @var bool
     * @required
     */
    public $isMaster = false;

    /**
     * @var string
     * @required
     */
    public $nodeType = 'landingpage';

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var array
     * @required
     */
    public $configuration = [];

    /**
     * @var Stream[]
     * @required
     */
    public $streams = [];

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     * @required
     */
    public $path = '';

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var integer
     * @required
     */
    public $sort = 0;

    /**
     * @var Node[]
     * @required
     */
    public $children = [];

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;

    /**
     * Optional error string during development
     *
     * @var ?string
     */
    public $error;

    /**
     * Page is live
     *
     * @var boolean
     * @transient
     */
    public $hasLivePage = false;

    /**
     * @var bool
     * @required
     */
    public $isDeleted = false;
}
