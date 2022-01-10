<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Context;

use Frontastic\Catwalk\FrontendBundle\Session\StatelessSessionHelper;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolver;

class FrontasticNextJsLocaleResolver implements LocaleResolver
{
    public function determineLocale(Request $request, Project $project): string
    {
        $locale = $request->get('locale');
        $availableLocales = $project->languages;

        if ($locale != null) {
            if (($matchedLocale = $this->getLocaleMatch($locale, $availableLocales)) !== null) {
                return $matchedLocale;
            }
        }

        // 2nd preference: Browser language
        if ($request->headers->has('Accept-Language')) {
            $locale = $this->determineFromHeader(
                $request->headers->get('Accept-Language'),
                $project->languages
            );

            if ($locale !== null) {
                return $locale;
            }
        }

        return $project->defaultLanguage;
    }

    private function determineFromHeader(string $acceptHeaderString, array $availableLocales): ?string
    {
        $acceptHeader = AcceptHeader::fromString($acceptHeaderString);
        foreach ($acceptHeader->all() as $acceptItem) {
            if (($matchedLocale = $this->getLocaleMatch($acceptItem->getValue(), $availableLocales)) !== null) {
                return $matchedLocale;
            }
        }

        return null;
    }

    private function getLocaleMatch(string $localeValue, array $availableLocales): ?string
    {
        $localeValue = strtolower(strtr($localeValue, ['-' => '_']));

        if (strpos($localeValue, '_') === false) {
            // Suffix for correct matching below
            $localeValue .= '_';
        }

        foreach ($availableLocales as $availableLocale) {
            if (strpos(strtolower($availableLocale), $localeValue) !== false) {
                return $availableLocale;
            }
        }

        return null;
    }
}
