<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Psr\SimpleCache\CacheInterface;

class ProductApiFactoryDecorator implements ProductApiFactory
{
    /**
     * @var ProductApiFactory
     */
    private $productApiFactory;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @var bool
     */
    private $debug = false;

    public function __construct(
        ProductApiFactory $productApiFactory,
        CacheInterface $cache,
        bool $debug = false
    ) {
        $this->productApiFactory = $productApiFactory;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function factor(Project $project): ProductApi
    {
        $api = $this->productApiFactory->factor($project);
        $api = new ProductApiWithoutInner($api);
        return new CachingProductApi($api, $this->cache, $this->debug);
    }
}
