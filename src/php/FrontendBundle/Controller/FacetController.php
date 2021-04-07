<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;

class FacetController
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
