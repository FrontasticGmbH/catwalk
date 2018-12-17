<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Common\JsonSerializer\ObjectEnhancer;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Symfony\Component\Routing\Router;

class UrlGenerator implements ObjectEnhancer
{
    private $objectRouterMap = [];

    public function __construct(array $objectRouterMap = [])
    {
        foreach ($objectRouterMap as $class => $router) {
            $this->registerRouter($class, $router);
        }
    }

    public function registerRouter(string $class, $router): void
    {
        $this->objectRouterMap[$class] = $router;
    }

    /**
     * @param object $object
     * @return array Map of properties to add to the serialization of $object
     */
    public function enhance($object): array
    {
        $class = get_class($object);

        if (!isset($this->objectRouterMap[$class])) {
            return [];
        }

        return [
            '_url' => $this->objectRouterMap[$class]->generateUrlFor($object),
        ];
    }
}
