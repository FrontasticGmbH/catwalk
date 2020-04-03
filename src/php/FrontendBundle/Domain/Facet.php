<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\FacetDefinition;

class Facet extends FacetDefinition
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
