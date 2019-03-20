<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQueryFactory;
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
        $query = ProductQueryFactory::queryFromParameters(
            ['locale' => $context->locale],
            $parameters,
            $stream->configuration
        );

        return $this->productApi->query($query);
    }
}
