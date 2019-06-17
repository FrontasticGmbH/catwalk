<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\Request;

class LocaleResolver
{
    public function determineLocale(Request $request, Project $project): string
    {
        $session = $request->getSession();

        // Update locale from request. Should that actually happen here?
        if (($locale = $request->get('locale')) && $session !== null) {
            $request->getSession()->set('locale', (trim($locale) === '') ? null: $locale);
        }

        // 1st preference: Manually selected locale from  session
        // TODO: Better use dedicated cookie? What about account setting?
        if ($session !== null && $session->has('locale')) {
            return $session->get('locale');
        }

        // 2nd preference: Browser language
        if ($request->headers->has('Accept-Language')) {
            $locale = $this->determineFromHeader($request->headers->get('Accept-Language'));

            if ($locale !== null) {
                return $locale;
            }
        }

        return $project->defaultLanguage;
    }

    private function determineFromHeader(string $acceptHeaderString): ?string
    {
        $acceptHeader = AcceptHeader::fromString($acceptHeaderString);
        foreach ($acceptHeader->all() as $acceptItem) {
            $value = strtr($acceptItem->getValue(), ['-' => '_']);

            // We only support full qualified locales
            if (strpos($value, '_') !== false) {
                return $value;
            }
        }
    }
}
