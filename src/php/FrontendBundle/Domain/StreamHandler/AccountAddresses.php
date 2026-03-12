<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;

class AccountAddresses extends StreamHandler
{
    private $accountApi;

    public function __construct(AccountApi $accountApi)
    {
        $this->accountApi = $accountApi;
    }

    public function getType(): string
    {
        return 'account-addresses';
    }

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        if (!$context->session->loggedIn) {
            return Create::promiseFor([]);
        }

        try {
            // While the account ID is also available in the stream configuration
            // this makes sure we always fetch the current accounts addresses.
            return Create::promiseFor(
                $this->accountApi->getAddresses(
                    $context->session->account
                )
            );
        } catch (\Throwable $exception) {
            return Create::rejectionFor($exception);
        }
    }
}
