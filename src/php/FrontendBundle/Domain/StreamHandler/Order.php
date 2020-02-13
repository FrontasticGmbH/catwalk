<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\CartApiBundle\Domain\CartApi;

class Order extends StreamHandler
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
        return 'order';
    }

    protected function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!isset($stream->configuration['order'])) {
            return null;
        }
        return $this->cartApi->getOrder($stream->configuration['order']);
    }
}
