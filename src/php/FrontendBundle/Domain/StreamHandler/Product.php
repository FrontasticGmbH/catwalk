<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!isset($stream->configuration['product'])) {
            return null;
        }

        return $this->productApi->getProduct(
            new ProductApi\Query\ProductQuery([
                'productId' => $stream->configuration['product'],
                'locale' => $context->locale,
            ])
        );
    }
}
