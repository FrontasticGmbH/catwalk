<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\ProductApi\EnabledFacetService as BaseEnabledFacetService;
use Psr\SimpleCache\CacheInterface;

class EnabledFacetService implements BaseEnabledFacetService
{
    private const FACETS_CACHE_KEY = 'frontastic.facets';

    /** @var FacetService */
    private $facetService;

    /** @var CacheInterface */
    private $cache;

    public function __construct(FacetService $facetService, CacheInterface $cache)
    {
        $this->facetService = $facetService;
        $this->cache = $cache;
    }

    public function getEnabledFacetDefinitions(): array
    {
        if (!($enabledFacets = $this->cache->get(self::FACETS_CACHE_KEY, false))) {
            $enabledFacets = $this->facetService->getEnabled();
            $this->cache->set(self::FACETS_CACHE_KEY, $enabledFacets, 600);
        }

        return $enabledFacets;
    }
}
