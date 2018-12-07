<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FacetController extends Controller
{
    public function allAction(): array
    {
        $facetService = $this->get(FacetService::class);

        return $facetService->getEnabled();
    }
}
