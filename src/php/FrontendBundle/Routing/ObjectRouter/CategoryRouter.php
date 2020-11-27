<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class CategoryRouter
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

    public function generateUrlFor(Category $category)
    {
        // <route id="Frontastic.Frontend.Master.Category.view" path="/c/{id}/{slug}" methods="GET">
        return $this->getRouter()->generate(
            'Frontastic.Frontend.Master.Category.view',
            [
                'slug' => $category->slug,
                'id' => $category->categoryId
            ]
        );
    }

    /**
     * @param Request $request
     * @return ?string ID of the category
     */
    public function identifyFrom(Request $request, Context $context): ?string
    {
        return $request->attributes->get('id');
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
