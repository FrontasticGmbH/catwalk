<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter\FilterQuery;
use Kore\DataObject\DataObject;

/**
 * An object of this type will be delivered to the data source extension which supports a `dynamic-filter` field.
 */
class DynamicFilterQuery extends DataObject
{
    /**
     * @var FilterQuery[]
     */
    public array $filterQueries = [];
}
