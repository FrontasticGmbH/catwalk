<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteRouters;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ProductRouter as FrontasticProductRouter;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ProductRouter extends FrontasticProductRouter
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var HooksService
     */
    private $hooksService;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ProductApi
     */
    private $productApi;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->container = $container;
    }

    public function generateUrlFor(Product $product): string
    {
        $params = $this->getHooksService()->callExpectArray('generateUrlForProductRouter', [$product]);
        return $this->getRouter()->generate('Frontastic.Frontend.Master.Product.view', $params);
    }

    public function identifyFrom(Request $request, Context $context): ?string
    {
        $attributes = $request->attributes->all();

        if (key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        $productQuery = $this->getHooksService()->callExpectObject(
            'identifyFromProductRouter',
            [
                new ProductQuery(),
                $attributes,
                $context
            ]
        );
        $product = $this->getProductApi()->getProduct($productQuery);

        if (!$product) {
            return null;
        }
        return $product->productId;
    }

    private function getHooksService(): HooksService
    {
        if (null === $this->hooksService) {
            $this->hooksService = $this->container->get(HooksService::class);
        }
        return $this->hooksService;
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
