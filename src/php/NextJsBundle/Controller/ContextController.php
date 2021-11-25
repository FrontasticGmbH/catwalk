<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\FrontendContext;

class ContextController
{
    public function indexAction(Context $context): FrontendContext
    {
        return $this->contextToFrontendContext($context);
    }

    private function contextToFrontendContext(Context $context): FrontendContext
    {
        return new FrontendContext([
            'locales' => $context->project->languages,
            'defaultLocale' => $context->project->defaultLanguage,
            'environment' => $context->applicationEnvironment(),
        ]);
    }
}
