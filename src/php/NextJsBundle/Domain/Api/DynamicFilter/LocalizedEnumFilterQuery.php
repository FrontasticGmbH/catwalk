<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class LocalizedEnumFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_LOCALIZED_ENUM;

    /**
     * @var string[] Collection of *keys* from the filter definition
     */
    public array $values = [];
}
