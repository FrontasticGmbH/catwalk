<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use GuzzleHttp\Promise\PromiseInterface;

class ProductSearchApiWithoutInner implements ProductSearchApi
{
    /** @var ProductSearchApi */
    private $aggregate;

    public function __construct(ProductSearchApi $aggregate)
    {
        $this->aggregate = $aggregate;
    }

    public function getAggregate(): ProductSearchApi
    {
        return $this->aggregate;
    }

    public function query(ProductQuery $query): PromiseInterface
    {
        return $this->aggregate
            ->query($query)
            ->then(function (Result $result): Result {
                $result->items = array_map([$this, 'removeInnerFromProduct'], $result->items);
                return $result;
            });
    }

    public function getSearchableAttributes(): PromiseInterface
    {
        return $this->aggregate->getSearchableAttributes();
    }

    public function getDangerousInnerClient()
    {
        return $this->aggregate->getDangerousInnerClient();
    }

    private function removeInnerFromProduct(Product $product): Product
    {
        $product->dangerousInnerProduct = null;
        foreach ($product->variants as $variant) {
            $variant->dangerousInnerVariant = null;
        }

        return $product;
    }
}
