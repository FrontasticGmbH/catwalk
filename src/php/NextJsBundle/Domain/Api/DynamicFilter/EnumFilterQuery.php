<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class EnumFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_ENUM;

    /**
     * Collection of *keys* from the filter definition
     * @var string[]
     */
    public array $values = [];
}
