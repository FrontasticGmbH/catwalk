<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

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

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        if (!$context->session->loggedIn) {
            return Promise\promise_for([]);
        }

        try {
            return Promise\promise_for(
                $this->cartApi->getOrders(
                    $context->session->account,
                    $context->locale
                )
            );
        } catch (\Throwable $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
