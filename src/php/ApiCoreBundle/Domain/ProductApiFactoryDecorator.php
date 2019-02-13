<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

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

    public function __construct(ProductApiFactory $productApiFactory, FacetService $facetService)
    {
        $this->productApiFactory = $productApiFactory;
        $this->facetService = $facetService;
    }

    public function factor(Customer $customer): ProductApi
    {
        $api = $this->productApiFactory->factor($customer);
        $this->setCommercetoolsOptions($api);
        return $api;
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

        $enabledFacets = $this->facetService->getEnabled();

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
        return $api;
    }
}
