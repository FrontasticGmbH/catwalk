<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

use Kore\DataObject\DataObject;

abstract class FilterQuery extends DataObject
{
    public string $filterId;

    /**
     * @var string One of {@link FilterDefinition::TYPE_*}
     */
    public string $type;
}
