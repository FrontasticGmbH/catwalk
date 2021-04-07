<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ProductApiFactoryDecorator;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryController extends AbstractController
{
    private ProductApiFactoryDecorator $productApiFactory;

    public function __construct(ProductApiFactoryDecorator $productApiFactory)
    {
        $this->productApiFactory = $productApiFactory;
    }

    public function listAction(Request $request, Context $context): array
    {
        $productApiFactory = $this->productApiFactory;

        $productApi = $productApiFactory->factor($context->project);

        $query = new CategoryQuery([
            'locale' => $context->locale,
            'limit' => $request->query->getInt('limit', 250),
            'offset' => $request->query->getInt('offset', 0),
            'parentId' => $request->query->get('parentId'),
            'slug' => $request->query->get('slug'),
        ]);

        return [
            'categories' => $productApi->getCategories($query),
        ];
    }
}
