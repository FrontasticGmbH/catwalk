<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\WishlistApiBundle\Domain\WishlistApi;
use Frontastic\Common\WishlistApiBundle\Domain\Wishlist;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class AccountWishlists extends StreamHandler
{
    private $wishlistApi;

    public function __construct(WishlistApi $wishlistApi)
    {
        $this->wishlistApi = $wishlistApi;
    }

    public function getType(): string
    {
        return 'account-wishlists';
    }

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!$context->session->loggedIn) {
            try {
                return [
                    $this->wishlistApi->getAnonymous(
                        $context->session->account->accountId,
                        $context->locale
                    )
                ];
            } catch (\OutOfBoundsException $e) {
                return [$this->wishlistApi->create(new Wishlist([
                    'name' => ['de' => 'Wunschzettel'],
                    'anonymousId' => session_id(),
                ]))];
            }
        }

        // While the wishlist ID is also available in the stream configuration
        // this makes sure we always fetch the current wishlists addresses.
        return $this->wishlistApi->getWishlists(
            $context->session->account->accountId,
            $context->locale
        );
    }
}
