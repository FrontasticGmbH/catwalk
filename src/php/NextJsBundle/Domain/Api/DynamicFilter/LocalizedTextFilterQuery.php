<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class LocalizedTextFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_LOCALIZED_TEXT;

    /**
     * @var string[] Array of query phrases. Typically just 1!
     */
    public array $values;
}
