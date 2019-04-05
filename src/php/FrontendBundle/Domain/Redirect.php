<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Redirect extends DataObject
{
    const TARGET_TYPE_NODE = 'node';
    const TARGET_TYPE_LINK = 'link';

    /**
     * @var string
     */
    public $redirectId;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $query;

    /**
     * @var string One of TARGET_TYPE_* constants
     */
    public $targetType;

    /**
     * @var string
     */
    public $target;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
