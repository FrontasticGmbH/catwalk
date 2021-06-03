<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteRouters;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\CategoryRouter as FrontasticCategoryRouter;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class CategoryRouter extends FrontasticCategoryRouter
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

    public function generateUrlFor(Category $category)
    {
        $params = $this->getHooksService()->callExpectArray('generateUrlForCategoryRouter', [$category]);
        return $this->getRouter()->generate('Frontastic.Frontend.Master.Category.view', $params);
    }

    /**
     * @param Request $request
     * @return ?string ID of the category
     */
    public function identifyFrom(Request $request, Context $context): ?string
    {
        $attributes = $request->attributes->all();

        if (key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        $categoryQuery = $this->getHooksService()->callExpectObject(
            'identifyFromCategoryRouter',
            [
                new CategoryQuery(),
                $attributes,
                $context,
            ]
        );

        $result = $this->getProductApi()->queryCategories($categoryQuery);

        if (empty($result->items)) {
            return null;
        }
        return $result->items[0]->categoryId;
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
