<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class EnumFilterDefinition extends FilterDefinition
{
    public string $type = self::TYPE_ENUM;

    /**
     * @var EnumFilterDefinitionValue[]
     */
    public array $values = [];
}
