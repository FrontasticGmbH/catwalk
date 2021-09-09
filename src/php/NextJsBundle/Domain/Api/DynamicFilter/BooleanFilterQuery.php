<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class BooleanFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_BOOLEAN;

    public bool $value;
}
