<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\FacetDefinition;

/**
 * @type
 */
class Facet extends FacetDefinition
{
    /**
     * @var string
     * @required
     */
    public $facetId;

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var int
     * @required
     */
    public $sort = -1;

    /**
     * @var bool
     * @required
     */
    public $isEnabled = false;

    /**
     * Translatable strings or null
     *
     * @var ?array
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
     * @required
     */
    public $metaData;

    /**
     * @var bool
     * @required
     */
    public $isDeleted = false;
}
