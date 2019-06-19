<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Psr\SimpleCache\CacheInterface;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query;

class CachingProductApi implements ProductApi
{
    /**
     * @var ProductApi
     */
    private $aggregate;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    public function __construct(ProductApi $aggregate, CacheInterface $cache)
    {
        $this->aggregate = $aggregate;
        $this->cache = $cache;
    }

    /**
     * @param \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery $query
     * @return \Frontastic\Common\ProductApiBundle\Domain\Category[]
     */
    public function getCategories(CategoryQuery $query): array
    {
        $cacheKey = 'frontastic.categories.' . md5(json_encode($query));
        if (!($result = $this->cache->get($cacheKey, false))) {
            $result = $this->aggregate->getCategories($query);
            $this->cache->set($cacheKey, $result, 600);
        }

        return $result;
    }

    /**
     * @param \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery $query
     * @return \Frontastic\Common\ProductApiBundle\Domain\ProductType[]
     */
    public function getProductTypes(ProductTypeQuery $query): array
    {
        $cacheKey = 'frontastic.types.' . md5(json_encode($query));
        if (!($result = $this->cache->get($cacheKey, false))) {
            $result = $this->aggregate->getProductTypes($query);
            $this->cache->set($cacheKey, $result, 600);
        }

        return $result;
    }

    /**
     * @param \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query
     * @return \Frontastic\Common\ProductApiBundle\Domain\Product
     */
    public function getProduct(ProductQuery $query): ?Product
    {
        // Do NOT cache product detail information, since it usually is crucial
        // to present the user with up-to-date information on the product
        // detail page.
        return $this->aggregate->getProduct($query);
    }

    /**
     * @param \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query
     * @return \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result|\Frontastic\Common\ProductApiBundle\Domain\Product[]
     */
    public function query(ProductQuery $query): Result
    {
        $cacheKey = 'frontastic.products.' . md5(json_encode($query));
        if (!($result = $this->cache->get($cacheKey, false))) {
            $result = $this->aggregate->query($query);
            $this->cache->set($cacheKey, $result, 600);
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
        return $this->aggregate->getDangerousInnerClient();
    }
}
