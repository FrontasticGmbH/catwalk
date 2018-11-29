<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacetController extends Controller
{
    public function allAction(): array
    {
        $facetService = $this->get('Frontastic\Catwalk\FrontendBundle\Domain\FacetService');

        return $facetService->getEnabled();
    }
}
