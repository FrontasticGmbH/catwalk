<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;

class RouteServiceWithPathsStorage extends RouteService
{
    private SiteBuilderPageService $siteBuilderPageService;

    public function __construct(
        CustomerService $customerService,
        SiteBuilderPageService $siteBuilderPageService,
        string $cacheDirectory
    ) {
        parent::__construct($customerService, $cacheDirectory);

        $this->siteBuilderPageService = $siteBuilderPageService;
    }

    public function storeRoutes(array $routes): void
    {
        parent::storeRoutes($routes);
        $this->siteBuilderPageService->storeSiteBuilderPagePathsFromRoutes($routes);
    }
}
