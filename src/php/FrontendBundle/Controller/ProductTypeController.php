<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;

class ProductTypeController
{
    private ProductApi $productApi;

    public function __construct(ProductApi $productApi)
    {
        $this->productApi = $productApi;
    }

    public function listAction(Context $context): array
    {
        $productApi = $this->productApi;

        $query = new ProductTypeQuery([
            'locale' => $context->locale,
        ]);

        return [
            'productTypes' => $productApi->getProductTypes($query),
        ];
    }
}
