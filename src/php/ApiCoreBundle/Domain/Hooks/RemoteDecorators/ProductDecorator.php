<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;

class ProductDecorator extends BaseImplementation
{
    use DecoratorCallTrait;

    public function beforeGetCategories(ProductApi $productApi, CategoryQuery $query): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_BEFORE_GET_CATEGORIES, [$query]);
    }

    public function afterGetCategories(ProductApi $productApi, array $categories): ?array
    {
        return $this->callExpectMultipleObjects(HooksCallBuilder::PRODUCT_AFTER_GET_CATEGORIES, [$categories]);
    }

    public function beforeQueryCategories(ProductApi $productApi, CategoryQuery $query): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_BEFORE_QUERY_CATEGORIES, [$query]);
    }

    public function afterQueryCategories(ProductApi $productApi, ?Result $result): ?Result
    {
        return $this->callExpectObject(HooksCallBuilder::PRODUCT_AFTER_QUERY_CATEGORIES, [$result]);
    }

    public function beforeGetProductTypes(ProductApi $productApi, ProductTypeQuery $query): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_BEFORE_GET_PRODUCT_TYPES, [$query]);
    }

    public function afterGetProductTypes(ProductApi $productApi, array $productTypes): ?array
    {
        return $this->callExpectMultipleObjects(HooksCallBuilder::PRODUCT_AFTER_GET_PRODUCT_TYPES, [$productTypes]);
    }

    public function beforeGetProduct(ProductApi $productApi, $query, string $mode = ProductApi::QUERY_SYNC): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_BEFORE_GET_PRODUCT, [$query, $mode]);
    }

    public function afterGetProduct(ProductApi $productApi, ?Product $product): ?Product
    {
        return $this->callExpectObject(HooksCallBuilder::PRODUCT_AFTER_GET_PRODUCT, [$product]);
    }
}
