<?php

namespace Frontastic\Catwalk\FrontendBundle\Twig;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\FrontendBundle\Domain\FacetService;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class NodeExtension extends \Twig_Extension
{
    private $nodeService;

    private $tasticService;

    private $facetService;

    private $productApi;

    private $context;

    public function __construct(
        NodeService $nodeService,
        TasticService $tasticService,
        FacetService $facetService,
        ProductApi $productApi,
        Context $context
    ) {
        $this->nodeService = $nodeService;
        $this->tasticService = $tasticService;
        $this->facetService = $facetService;
        $this->productApi = $productApi;
        $this->context = $context;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig_Function('frontastic_tree', [$this->nodeService, 'getTree']),
            new \Twig_Function('completeInformation', [$this, 'completeInformation']),
        ];
    }

    public function completeInformation(array $variables): array
    {
        return array_merge(
            [
                'node' => null,
                'page' => null,
                'data' => null,
                'tastics' => $this->tasticService->getAll(),
                'facets' => $this->facetService->getEnabled(),
                'categories' => $this->productApi->getCategories(new CategoryQuery([
                    'locale' => $this->context->locale,
                    'limit' => 100,
                ])),
            ],
            $variables
        );
    }
}
