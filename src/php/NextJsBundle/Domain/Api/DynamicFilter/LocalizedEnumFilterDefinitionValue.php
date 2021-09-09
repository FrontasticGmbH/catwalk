<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

use Kore\DataObject\DataObject;

class LocalizedEnumFilterDefinitionValue extends DataObject
{
    public string $key;

    /**
     * @var string[] Labels indexed by project locale
     */
    public array $labels = [];
}
