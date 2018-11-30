<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ProductList extends StreamHandler
{
    private $productApi;

    public function __construct(ProductApi $productApi)
    {
        $this->productApi = $productApi;
    }

    public function getType(): string
    {
        return 'product-list';
    }

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        $query = $this->createProductQuery(
            // FIXME: String for empty query is Semknox specific and leads to side effects regarding filters
            // ['locale' => 'en_GB@euro', 'query' => '_#'],
            ['locale' => $context->locale],
            $parameters,
            $stream->configuration
        );

        return $this->productApi->query($query);
    }

    private function createProductQuery(array $defaults, array $parameters, array $streamConfig)
    {
        // TODO: This should be moved the ProductAPI to support users creating and merging queries

        $queryParameters = array_merge(
            $defaults,
            $parameters,
            $streamConfig
        );

        $rawFacets = array_merge(
            $defaults['facets'] ?? [],
            $parameters['facets'] ?? [],
            $streamConfig['facets'] ?? []
        );

        $queryParameters['facets'] = [];
        foreach ($rawFacets as $facetConfig) {
            $queryParameters['facets'][] = $this->createFacet($facetConfig);
        }

        return new ProductQuery($queryParameters);
    }

    private function createFacet(array $facetConfig) : ProductApi\Query\Facet
    {
        $facetType = $facetConfig['type'];
        unset($facetConfig['type']);

        switch ($facetType) {
            case ProductApi\Facets::TYPE_RANGE:
                return new ProductApi\Query\RangeFacet($facetConfig);

            case ProductApi\Facets::TYPE_TERM:
                return new ProductApi\Query\TermFacet($facetConfig);
        }

        throw new \RuntimeException("Unknown facet type '{$facetType}'");
    }
}
