<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Psr\SimpleCache\CacheInterface;

use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;

class ProductApiFactoryDecorator implements ProductApiFactory
{
    /**
     * @var ProductApiFactory
     */
    private $productApiFactory;

    /**
     * @var FacetService
     */
    private $facetService;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    /**
     * @var bool
     */
    private $debug = false;

    const FACETS_CACHE_KEY = 'frontastic.facets';

    public function __construct(
        ProductApiFactory $productApiFactory,
        FacetService $facetService,
        CacheInterface $cache,
        bool $debug = false
    ) {
        $this->productApiFactory = $productApiFactory;
        $this->facetService = $facetService;
        $this->cache = $cache;
        $this->debug = $debug;
    }

    public function factor(Customer $customer): ProductApi
    {
        $api = $this->productApiFactory->factor($customer);
        $this->setCommercetoolsOptions($api);
        $api = new ProductApiWithoutInner($api);
        return new CachingProductApi($api, $this->cache, $this->debug);
    }

    private function setCommercetoolsOptions(ProductApi $api): void
    {
        // KN: Sorry :D
        while (method_exists($api, 'getAggregate')) {
            $api = $api->getAggregate();
        }

        if (!($api instanceof ProductApi\Commercetools)) {
            return;
        }

        if (!($enabledFacets = $this->cache->get(self::FACETS_CACHE_KEY, false))) {
            $enabledFacets = $this->facetService->getEnabled();
            $this->cache->set(self::FACETS_CACHE_KEY, $enabledFacets, 600);
        }

        $facetConfig = [];
        foreach ($enabledFacets as $facet) {
            $facetConfig[] = [
                'attributeId' => $facet->attributeId,
                'attributeType' => $facet->attributeType,
            ];
        }

        /** @var ProductApi\Commercetools $api */
        $api->setOptions(new ProductApi\Commercetools\Options([
            'facetsToQuery' => $facetConfig,
        ]));
    }

    public function factorFromConfiguration(array $config): ProductApi
    {
        $api = $this->productApiFactory->factorFromConfiguration($config);
        $this->setCommercetoolsOptions($api);
        return new CachingProductApi($api, $this->cache);
    }
}
