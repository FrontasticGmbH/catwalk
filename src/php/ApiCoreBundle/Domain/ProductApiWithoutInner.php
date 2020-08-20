<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductApiBundle\Domain\ProductType;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * A Product API decorator that removes the `dangerousInner*` from the results
 *
 * This makes sure that the dangerousInnerProduct as well as dangerousInnerVariant are removed.
 * This improves caching, as we experienced a lot of CPU time being consumed while caching the large innerProduct.
 * It also reduces the cache size.
 * If you need to access dangerousInner*, try doing this in a lifecycle event listener.
 */
class ProductApiWithoutInner implements ProductApi
{
    /**
     * @var ProductApi
     */
    private $innerProductApi;

    public function __construct(ProductApi $innerProductApi)
    {
        $this->innerProductApi = $innerProductApi;
    }

    /**
     * @param CategoryQuery $query
     * @return Category[]
     */
    public function getCategories(CategoryQuery $query): array
    {
        $categories = $this->innerProductApi->getCategories($query);

        foreach ($categories as $category) {
            $category->dangerousInnerCategory = null;
        }

        return $categories;
    }


    public function queryCategories(CategoryQuery $query): Result
    {
        $categories = $this->getCategories($query);

        return new Result([
            'count' => count($categories),
            'items' => $categories,
            'query' => clone($query)
        ]);
    }

    /**
     * @param ProductTypeQuery $query
     * @return ProductType[]
     */
    public function getProductTypes(ProductTypeQuery $query): array
    {
        $productTypes = $this->innerProductApi->getProductTypes($query);

        foreach ($productTypes as $productType) {
            $productType->dangerousInnerProductType = null;
        }

        return $productTypes;
    }

    public function getProduct($query, string $mode = self::QUERY_SYNC): ?object
    {
        $result = $this->innerProductApi->getProduct($query, $mode);

        if ($result instanceof Product) {
            $this->removeInnerFromProduct($result);
            return $result;
        }

        if ($result instanceof PromiseInterface) {
            $result->then(
                function (Product $product) {
                    return $this->removeInnerFromProduct($product);
                },
                function ($value) {
                    return $value;
                }
            );
        }

        // not sure what the result is - probably null?
        // just return it ;-)
        return $result;
    }

    /**
     * @param ProductQuery $query
     * @param string $mode One of the QUERY_* connstants. Execute the query synchronously or asynchronously?
     * @return Result|PromiseInterface A result when the mode is sync and a promise if the mode is async.
     */
    public function query(ProductQuery $query, string $mode = self::QUERY_SYNC): object
    {
        $result = $this->innerProductApi->query($query, $mode);
        if ($result instanceof Result) {
            $result->items = array_map([$this, 'removeInnerFromProduct'], $result->items);
            return $result;
        }

        if ($result instanceof PromiseInterface) {
            $result->then(
                function (Result $queryResult) {
                    $queryResult->items = array_map([$this, 'removeInnerFromProduct'], $queryResult->items);
                    return $queryResult;
                },
                function ($value) {
                    return $value;
                }
            );
        }

        return $result;
    }

    /**
     * Get *dangerous* inner client
     *
     * This method exists to enable you to use features which are not yet part
     * of the abstraction layer.
     *
     * Be aware that any usage of this method might seriously hurt backwards
     * compatibility and the future abstractions might differ a lot from the
     * vendor provided abstraction.
     *
     * Use this with care for features necessary in your customer and talk with
     * Frontastic about provising an abstraction.
     *
     * @return mixed
     */
    public function getDangerousInnerClient()
    {
        return $this->innerProductApi->getDangerousInnerClient();
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
