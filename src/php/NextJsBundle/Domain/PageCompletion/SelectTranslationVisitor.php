<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldConfiguration;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class SelectTranslationVisitor implements FieldVisitor
{
    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function processField(FieldConfiguration $configuration, $value, array $fieldPath)
    {
        if (!$configuration->isTranslatable()) {
            return $value;
        }

        return LocalizedValuePicker::getValueForCurrentLocale($this->context, $value);
    }
}
