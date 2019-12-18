<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Schema extends DataObject
{
    /**
     * @var string
     */
    public $schemaId;

    /**
     * @var string
     */
    public $schemaType;

    /**
     * @var array
     */
    public $schema;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
