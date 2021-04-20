<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class PatternLibraryController
{
    public function indexAction(Context $context)
    {
        if ($context->environment !== 'dev') {
            // @TODO: Require some kind of authorization token
        }

        return [];
    }
}
