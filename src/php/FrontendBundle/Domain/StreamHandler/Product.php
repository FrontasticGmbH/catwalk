<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;

class Product extends StreamHandler
{
    private $productApi;

    public function __construct(ProductApi $productApi)
    {
        $this->productApi = $productApi;
    }

    public function getType(): string
    {
        return 'product';
    }

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        if (!isset($stream->configuration['product'])) {
            return Promise\promise_for(null);
        }

        $query = ProductApi\Query\SingleProductQuery::byProductIdWithLocale(
            $stream->configuration['product'],
            $context->locale
        );

        $res = $this->productApi->getProduct(
            $query,
            ProductApi::QUERY_ASYNC
        );

        // TODO: This needs a more structural fix
        if (!$res instanceof PromiseInterface) {
            return new FulfilledPromise($res);
        }

        return $res;
    }
}
