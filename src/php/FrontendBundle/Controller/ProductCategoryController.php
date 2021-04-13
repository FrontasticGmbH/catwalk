<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Symfony\Component\HttpFoundation\Request;

class ProductCategoryController
{
    private ProductApi $productApi;

    public function __construct(ProductApi $productApi)
    {
        $this->productApi = $productApi;
    }

    public function listAction(Request $request, Context $context): array
    {
        $query = new CategoryQuery([
            'locale' => $context->locale,
            'limit' => $request->query->getInt('limit', 250),
            'offset' => $request->query->getInt('offset', 0),
            'parentId' => $request->query->get('parentId'),
            'slug' => $request->query->get('slug'),
        ]);

        return [
            'categories' => $this->productApi->getCategories($query),
        ];
    }
}
