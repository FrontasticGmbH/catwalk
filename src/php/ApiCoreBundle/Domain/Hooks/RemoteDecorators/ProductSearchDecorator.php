<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\ProjectApiBundle\Domain\Attribute;

class ProductSearchDecorator extends BaseImplementation
{
    use DecoratorCallTrait;

    public function beforeGetSearchableAttributes(ProductSearchApi $productSearchApi): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_SEARCH_BEFORE_GET_SEARCHABLE_ATTRIBUTES, []);
    }

    /**
     * @param ProductSearchApi $productSearchApi
     * @return ?Attribute[]
     */
    public function afterGetSearchableAttributes(ProductSearchApi $productSearchApi, ?array $attributes): ?array
    {
        return $this->callExpectObject(
            HooksCallBuilder::PRODUCT_SEARCH_AFTER_GET_SEARCHABLE_ATTRIBUTES,
            [$attributes]
        );
    }

    public function beforeQuery(ProductSearchApi $productSearchApi, ProductQuery $query): ?array
    {
        return $this->callExpectList(HooksCallBuilder::PRODUCT_SEARCH_BEFORE_QUERY, [$query]);
    }

    public function afterQuery(ProductSearchApi $productSearchApi, ?Result $result): ?Result
    {
        return $this->callExpectObject(HooksCallBuilder::PRODUCT_SEARCH_AFTER_QUERY, [$result]);
    }
}
