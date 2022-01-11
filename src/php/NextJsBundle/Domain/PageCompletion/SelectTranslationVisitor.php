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

        if (!is_array($value)) {
            // TODO: Is this correct?
            return $value;
        }

        // Text available in currently selected locale
        if (isset($value[$this->context->locale])) {
            return $value[$this->context->locale];
        }

        // Text available in language of current locale
        $language = substr(
            $this->context->locale,
            0,
            strpos($this->context->locale, '_')
        );
        if (isset($value[$language])) {
            return $value[$language];
        }

        // Text available in default locale
        if (isset($value[$this->context->project->defaultLanguage])) {
            return $value[$this->context->project->defaultLanguage];
        }

        // Text available in language of default locale
        $language = substr(
            $this->context->project->defaultLanguage,
            0,
            strpos($this->context->project->defaultLanguage, '_')
        );
        if (isset($value[$language])) {
            return $value[$language];
        }

        // No translation could be found matching the context, so return null
        return null;
    }
}
