<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Symfony\Component\Config\Loader\Loader as BaseLoader;
use Symfony\Component\Routing\RouteCollection;

class FrontasticNextJsDummyLoader extends BaseLoader
{
    public function supports($resource, $type = null): bool
    {
        return $type === 'frontastic-nextjs';
    }

    public function load($resource, $type = null): RouteCollection
    {
        return new RouteCollection();
    }
}
