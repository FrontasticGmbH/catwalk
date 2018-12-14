<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Common\JsonSerializer\ObjectEnhancer;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Symfony\Component\Routing\Router;

class UrlGenerator implements ObjectEnhancer
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param object $object
     * @return array Map of properties to add to the serialization of $object
     */
    public function enhance($object): array
    {
        if ($object instanceof Product) {
            return [
                '_url' => $this->generateProductUrl($object),
            ];
        }

        return [];
    }

    private function generateProductUrl(Product $product): string
    {
        return $this->router->generate(
            'Frontastic.Frontend.Master.Product.view',
            [
                'url' => strtr($product->slug, [
                    '_' => '/',
                    // Just for testing, no need in the future
                    '-' => '/'
                ]),
                'id' => $product->productId,
            ]
        );
    }
}
