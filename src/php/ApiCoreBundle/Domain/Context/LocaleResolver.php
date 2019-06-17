<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\Request;

class LocaleDeterminer
{
    public function determineLocale(Request $request, Project $project): string
    {
        $session = $request->getSession();

        // Update locale from request. Should that actually happen here?
        if (($locale = $request->get('locale')) && $session !== null) {
            $request->getSession()->set('locale', (trim($locale) === '') ? null: $locale);
        }

        // 1st preference: Manually selected locale from  session
        if ($session !== null && $session->has('locale')) {
            return $session->get('locale');
        }

        return $project->defaultLanguage;
    }
}
