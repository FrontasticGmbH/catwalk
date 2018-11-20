<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class AccountOrders extends StreamHandler
{
    private $cartApi;

    public function __construct(CartApi $cartApi)
    {
        $this->cartApi = $cartApi;
    }

    public function getType(): string
    {
        return 'account-orders';
    }

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!$context->session->loggedIn) {
            return [];
        }

        // While the cart ID is also available in the stream configuration
        // this makes sure we always fetch the current carts addresses.
        return $this->cartApi->getOrders(
            $context->session->account->accountId
        );
    }
}
