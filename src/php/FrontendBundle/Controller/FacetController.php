<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FacetController extends AbstractController
{
    private FacetService $facetService;

    public function __construct(FacetService $facetService)
    {
        $this->facetService = $facetService;
    }

    public function allAction(): array
    {
        $facetService = $this->facetService;

        return $facetService->getEnabled();
    }
}
