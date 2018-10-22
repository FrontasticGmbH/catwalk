<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TasticController extends Controller
{
    public function allAction(): array
    {
        $tasticService = $this->get('Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService');

        return $tasticService->getAll();
    }
}
