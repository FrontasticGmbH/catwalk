<?php

namespace Frontastic\Catwalk\NextJsBundle\Routing;
use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\RouteCollection;

/**
 * Using this loader allows us to dynamically load the Frontastic Next.js routes only when the bundle is enabled.
 */
class Loader extends BaseLoader
{
    public function supports($resource, $type = null): bool
    {
        return $type === 'frontastic-nextjs';
    }

    public function load($resource, $type = null): RouteCollection
    {
        $routes   = new RouteCollection();
        $resource = '@FrontasticCatwalkNextJsBundle/Resources/config/routing.xml';
        $type     = 'xml';

        $routes->addCollection($this->import($resource, $type));

        return $routes;
    }
}
