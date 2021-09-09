<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

use Kore\DataObject\DataObject;

class EnumFilterDefinitionValue extends DataObject
{
    public string $key;

    public string $label;
}
