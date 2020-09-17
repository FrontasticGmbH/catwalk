<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiBase;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\SimpleCache\CacheInterface;

class CachingProductSearchApi extends ProductSearchApiBase
{
    /** @var ProductSearchApi */
    private $aggregate;

    /** @var CacheInterface */
    private $cache;

    /** @bool */
    private $debug;

    public function __construct(ProductSearchApi $aggregate, CacheInterface $cache, $debug = false)
    {
        $this->aggregate = $aggregate;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function getAggregate(): ProductSearchApi
    {
        return $this->aggregate;
    }

    protected function queryImplementation(ProductQuery $query): PromiseInterface
    {
        $cacheKey = 'frontastic.products.' . md5(json_encode($query));

        $cacheEntry = $this->cache->get($cacheKey, null);
        if (!$this->debug && $cacheEntry !== null) {
            return Promise\promise_for($cacheEntry);
        }

        return $this->aggregate
            ->query($query)
            ->then(function ($result) use ($cacheKey) {
                $this->cache->set($cacheKey, $result, 600);
                return $result;
            });
    }

    protected function getSearchableAttributesImplementation(): PromiseInterface
    {
        return $this->aggregate->getSearchableAttributes();
    }

    public function getDangerousInnerClient()
    {
        return $this->aggregate->getDangerousInnerClient();
    }
}
