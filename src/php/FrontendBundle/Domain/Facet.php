<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Facet extends DataObject
{
    /**
     * @var string
     */
    public $facetId;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var string
     */
    public $attributeId;

    /**
     * @var string
     */
    public $attributeType;

    /**
     * @var int
     */
    public $sort = -1;

    /**
     * @var bool
     */
    public $isEnabled = false;

    /**
     * @var array|null Translatable strings or null
     */
    public $label;

    /**
     * @var string
     */
    public $displayType;

    /**
     * @var string
     */
    public $urlIdentifier;

    /**
     * @var array
     */
    public $facetOptions;

    /**
     * @var MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
