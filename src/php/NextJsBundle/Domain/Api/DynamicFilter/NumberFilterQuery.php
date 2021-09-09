<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class NumberFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_NUMBER;

    /**
     * @var int|null Range minimum, null for unbounded.
     */
    public ?int $min;

    /**
     * @var int|null Range maximum, null for unbounded.
     */
    public ?int $max;
}
