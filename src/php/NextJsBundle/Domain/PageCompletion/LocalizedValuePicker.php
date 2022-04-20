<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\PageCompletion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class LocalizedValuePicker
{


    private function __construct()
    {
    }

    public static function getValueForCurrentLocale(Context $context, $value)
    {
        if (!is_array($value)) {
            return $value;
        }

        // Text available in currently selected locale
        if (isset($value[$context->locale])) {
            return $value[$context->locale];
        }

        // Text available in language of current locale
        $language = substr(
            $context->locale,
            0,
            strpos($context->locale, '_')
        );
        if (isset($value[$language])) {
            return $value[$language];
        }

        // Text available in default locale
        if (isset($value[$context->project->defaultLanguage])) {
            return $value[$context->project->defaultLanguage];
        }

        // Text available in language of default locale
        $language = substr(
            $context->project->defaultLanguage,
            0,
            strpos($context->project->defaultLanguage, '_')
        );
        if (isset($value[$language])) {
            return $value[$language];
        }

        if (count($value) > 0) {
            return reset($value);
        }

        // No translation could be found matching the context, so return null
        return null;
    }
}
