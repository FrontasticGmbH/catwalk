<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQueryFactory;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use GuzzleHttp\Promise\PromiseInterface;

class ProductList extends StreamHandler
{
    private $productSearchApi;

    public function __construct(ProductSearchApi $productSearchApi)
    {
        $this->productSearchApi = $productSearchApi;
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

        return $this->productSearchApi->query($query);
    }
}
