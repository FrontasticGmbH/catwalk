<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!$context->session->loggedIn) {
            return [];
        }

        // While the account ID is also available in the stream configuration
        // this makes sure we always fetch the current accounts addresses.
        return $this->accountApi->getAddresses(
            $context->session->account->accountId
        );
    }
}
