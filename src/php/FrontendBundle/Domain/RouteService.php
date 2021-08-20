<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface RouteService
{
    /**
     * @return Route[]
     */
    public function getRoutes(): array;

    public function storeRoutes(array $routes): void;

    /**
     * @param Node[] $nodes
     */
    public function rebuildRoutes(array $nodes): void;

    /**
     * @param Node[] $nodes
     * @return Route[]
     */
    public function generateRoutes(array $nodes): array;
}
