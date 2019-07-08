<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\WishlistApiBundle\Domain\Wishlist;
use Frontastic\Common\WishlistApiBundle\Domain\WishlistApi;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

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

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        try {
            if (!$context->session->loggedIn) {
                try {
                    return Promise\promise_for([
                        $this->wishlistApi->getAnonymous(
                            $context->session->account->accountId,
                            $context->locale
                        ),
                    ]);
                } catch (\OutOfBoundsException $e) {
                    return Promise\promise_for([
                        $this->wishlistApi->create(
                            new Wishlist([
                                'name' => ['de' => 'Wunschzettel'],
                                'anonymousId' => session_id(),
                            ]),
                            $context->locale
                        ),
                    ]);
                }
            }

            // While the wishlist ID is also available in the stream configuration
            // this makes sure we always fetch the current wishlists addresses.
            return Promise\promise_for(
                $this->wishlistApi->getWishlists(
                    $context->session->account->accountId,
                    $context->locale
                )
            );
        } catch (\Exception $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
