<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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
