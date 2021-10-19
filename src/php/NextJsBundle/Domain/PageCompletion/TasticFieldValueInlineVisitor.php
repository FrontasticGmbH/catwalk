<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldService;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

/**
 * NOTE: This could actually be done directly in {@link TasticFieldService}, but we don't do it there for BC
 * reasons with Frontastic React.
 */
class TasticFieldValueInlineVisitor implements FieldVisitor
{
    private array $tasticFieldData;

    public function __construct(array $tasticFieldData)
    {
        $this->tasticFieldData = $tasticFieldData;
    }

    public function processField(FieldConfiguration $configuration, $value, $fieldPath)
    {
        // Groups always have an (at least empty) handled value (legacy), but we don't want it
        // Nested fields in the group will still be handled properly by the caller
        if ($configuration->getType() === 'group') {
            return $value;
        }

        $fieldData = $this->tasticFieldData;
        foreach ($fieldPath as $fieldPathElement) {
            if (!isset($fieldData[$fieldPathElement])) {
                return $value;
            }
            $fieldData = $fieldData[$fieldPathElement];
        }
        return [
            'studioValue' => $value,
            'handledValue' => $fieldData,
        ];
    }
}
