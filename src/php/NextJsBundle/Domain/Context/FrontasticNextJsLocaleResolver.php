<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Context;

use Frontastic\Catwalk\FrontendBundle\Session\StatelessSessionHelper;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context\LocaleResolverInterface;

class FrontasticNextJsLocaleResolver implements LocaleResolverInterface
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

        return $project->defaultLanguage;
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
