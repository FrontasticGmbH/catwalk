<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ProductRouter
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var ProductApi
     */
    private $productApi;

    public function __construct(Router $router, ProductApi $productApi)
    {
        $this->router = $router;
        $this->productApi = $productApi;
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
                'identifier' => $product->variants[0]->sku,
            ]
        );
    }

    /**
     * @param Request $request
     * @return string|null ID of the product
     */
    public function identifyFrom(Request $request, Context $context): ?string
    {
        $product = $this->productApi->getProduct(new ProductQuery([
            'locale' => $context->locale,
            'sku' => $request->attributes->get('identifier'),
        ]));

        if (!$product) {
            return null;
        }
        return $product->productId;
    }
}
