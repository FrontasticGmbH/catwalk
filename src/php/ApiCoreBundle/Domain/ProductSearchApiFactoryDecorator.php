<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Psr\SimpleCache\CacheInterface;

class ProductSearchApiFactoryDecorator implements ProductSearchApiFactory
{
    /** @var ProductSearchApiFactory */
    private $productSearchApiFactory;

    /** @var CacheInterface */
    private $cache;

    /** @var bool */
    private $debug;

    public function __construct(
        ProductSearchApiFactory $productSearchApiFactory,
        CacheInterface $cache,
        bool $debug = false
    ) {
        $this->productSearchApiFactory = $productSearchApiFactory;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function factor(Project $project): ProductSearchApi
    {
        $api = $this->productSearchApiFactory->factor($project);
        $api = new ProductSearchApiWithoutInner($api);
        $api = new CachingProductSearchApi($api, $this->cache, $this->debug);

        return $api;
    }
}
