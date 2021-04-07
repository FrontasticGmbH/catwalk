<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatternLibraryController extends AbstractController
{
    public function indexAction(Context $context)
    {
        if ($context->environment !== 'dev') {
            // @TODO: Require some kind of authorization token
        }

        return [];
    }
}
