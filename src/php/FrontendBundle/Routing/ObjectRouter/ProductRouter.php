<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        /*
         * IMPORTANT: We must use the container here, otherwise we try to load
         * the ContextService in context free situations.
         */
        $this->container = $container;
    }

    public function generateUrlFor(Product $product)
    {
        return $this->getRouter()->generate(
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
        $product = $this->getProductApi()->getProduct(new ProductQuery([
            'locale' => $context->locale,
            'sku' => $request->attributes->get('identifier'),
        ]));

        if (!$product) {
            return null;
        }
        return $product->productId;
    }

    private function getProductApi(): ProductApi
    {
        if (null === $this->productApi) {
            $this->productApi = $this->container->get('frontastic.catwalk.product_api');
        }
        return $this->productApi;
    }

    private function getRouter(): Router
    {
        if (null === $this->router) {
            $this->router = $this->container->get('router');
        }
        return $this->router;
    }
}
