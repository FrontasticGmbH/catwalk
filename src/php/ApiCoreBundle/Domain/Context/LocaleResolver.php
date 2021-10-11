<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

use Frontastic\Catwalk\FrontendBundle\Session\StatelessSessionHelper;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;

class LocaleResolver
{
    public function determineLocale(Request $request, Project $project): string
    {
        $session = StatelessSessionHelper::hasSession($request)
            ? $request->getSession()
            : null;

        // Update locale from request. Should that actually happen here?
        if (($locale = $request->get('locale')) && $session !== null) {
            $session->set('locale', (trim($locale) === '') ? null: $locale);
        }

        // 1st preference: Manually selected locale from  session
        // TODO: Better use dedicated cookie? What about account setting?
        if ($session !== null && $session->has('locale')) {
            return $session->get('locale');
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
