<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\CartApiBundle\Domain\CartApi;

class Cart extends StreamHandler
{
    /**
     * @var CartApi
     */
    private $cartApi;

    public function __construct(CartApi $cartApi)
    {
        $this->cartApi = $cartApi;
    }

    public function getType(): string
    {
        return 'cart';
    }

    protected function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!isset($stream->configuration['cart'])) {
            return null;
        }

        // @TODO: Implement
        return null;
    }
}
