<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQueryFactory;
use GuzzleHttp\Promise\PromiseInterface;

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

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        $query = ProductQueryFactory::queryFromParameters(
            ['locale' => $context->locale],
            $parameters,
            $stream->configuration
        );

        return $this->productApi->query($query, ProductApi::QUERY_ASYNC);
    }
}
