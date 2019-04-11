<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;

use Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler;
use Frontastic\Common\WishlistApiBundle\Domain\WishlistApi;
use Frontastic\Common\WishlistApiBundle\Domain\Wishlist;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class AccountWishlistsFieldHandler extends TasticFieldHandler
{
    private $wishlistApi;

    public function __construct(WishlistApi $wishlistApi)
    {
        $this->wishlistApi = $wishlistApi;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return 'account-wishlists';
    }

    /**
     * @param Context $context
     * @param mixed $fieldValue
     * @return mixed Handled value
     */
    public function handle(Context $context, $fieldValue)
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
