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

    public function processField(FieldConfiguration $configuration, $value)
    {
        if (!$configuration->isTranslatable()) {
            return $value;
        }

        if (!is_array($value)) {
            // TODO: Is this correct?
            return $value;
        }

        if (isset($value[$this->context->locale])) {
            return $value[$this->context->locale];
        }

        if (isset($value[$this->context->project->defaultLanguage])) {
            return $value[$this->context->project->defaultLanguage];
        }
    }
}
