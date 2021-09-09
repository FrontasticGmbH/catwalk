<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class TextFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_TEXT;

    /**
     * @var string[] Array of query phrases. Typically just 1!
     */
    public array $values;
}
