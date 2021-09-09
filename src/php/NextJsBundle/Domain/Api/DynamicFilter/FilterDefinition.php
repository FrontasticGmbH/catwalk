<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicFilter;

use Kore\DataObject\DataObject;

abstract class FilterDefinition extends DataObject
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_NUMBER = 'number';
    const TYPE_MONEY = 'money';
    const TYPE_TEXT = 'text';
    const TYPE_LOCALIZED_TEXT = 'localizedText';
    const TYPE_ENUM = 'enum';
    const TYPE_LOCALIZED_ENUM = 'localizedEnum';

    public string $filterId;

    /**
     * @var string One of self::TYPE_*
     */
    public string $type;

    /**
     * @var string[] Labels for this filter in Frontastic studio, indexed by project
     */
    public array $label;
}
