<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class MoneyFilterQuery extends FilterQuery
{
    public string $type = FilterDefinition::TYPE_MONEY;

    /**
     * @var int|null Range minimum in cents, null for unbounded
     */
    public ?int $min = null;

    /**
     * @var int|null Range maximum in cents, null for unbounded
     */
    public ?int $max = null;
}
