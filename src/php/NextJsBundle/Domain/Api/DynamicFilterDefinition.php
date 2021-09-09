<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter\FilterDefinition;
use Kore\DataObject\DataObject;

/**
 * An object of this type is to be returned by the API endpoint of `dynamic-filter` field type.
 */
class DynamicFilterDefinition extends DataObject
{
    /**
     * @var FilterDefinition[]
     */
    public array $filterDefinitions = [];
}
