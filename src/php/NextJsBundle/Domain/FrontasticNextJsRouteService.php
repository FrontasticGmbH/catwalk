<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\FrontendBundle\Domain\FrontasticReactRouteService;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Route;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;

class FrontasticNextJsRouteService implements RouteService
{
    private SiteBuilderPageService $siteBuilderPageService;
    private FrontasticReactRouteService $frontasticReactRouteService;

    public function __construct(
        SiteBuilderPageService $siteBuilderPageService,
        FrontasticReactRouteService $frontasticReactRouteService
    ) {
        $this->siteBuilderPageService = $siteBuilderPageService;
        $this->frontasticReactRouteService = $frontasticReactRouteService;
    }

    public function storeRoutes(array $routes): void
    {
        $this->siteBuilderPageService->storeSiteBuilderPagePathsFromRoutes($routes);
    }

    public function getRoutes(): array
    {
        // We don't use the Frontastic React way of routing anymore
        return [];
    }

    public function rebuildRoutes(array $nodes): void
    {
        $this->storeRoutes($this->generateRoutes($nodes));
    }

    public function generateRoutes(array $nodes): array
    {
        return $this->frontasticReactRouteService->generateRoutes($nodes);
    }
}
