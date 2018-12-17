<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter;

use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ProductRouter
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function generateUrlFor(Product $product)
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

    public function parseQueryFrom(Request $request)
    {

        return new ProductQuery([
            'productId' => $request->get('id'),
        ]);
    }
}
