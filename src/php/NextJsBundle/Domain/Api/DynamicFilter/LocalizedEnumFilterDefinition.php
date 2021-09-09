<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

class LocalizedEnumFilterDefinition extends FilterDefinition
{
    public string $type = self::TYPE_LOCALIZED_ENUM;

    /**
     * @var LocalizedEnumFilterDefinitionValue[]
     */
    public array $values = [];
}
