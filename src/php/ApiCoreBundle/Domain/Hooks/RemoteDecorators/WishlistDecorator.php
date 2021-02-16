<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\WishlistApiBundle\Domain\LineItem;
use Frontastic\Common\WishlistApiBundle\Domain\Wishlist;
use Frontastic\Common\WishlistApiBundle\Domain\WishlistApi;
use Frontastic\Common\WishlistApiBundle\Domain\WishlistApi\LifecycleEventDecorator\BaseImplementationV2;

class WishlistDecorator extends BaseImplementationV2
{
    use DecoratorCallTrait;

    public function beforeGetWishlist(WishlistApi $wishlistApi, string $wishlistId, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::WISHLIST_BEFORE_GET_WISHLIST, [$wishlistId, $locale]);
    }

    public function afterGetWishlist(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_AFTER_GET_WISHLIST, [$wishlist]);
    }

    public function beforeGetAnonymous(WishlistApi $wishlistApi, string $anonymousId, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::WISHLIST_BEFORE_GET_ANONYMOUS, [$anonymousId, $locale]);
    }

    public function afterGetAnonymous(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_AFTER_GET_ANONYMOUS, [$wishlist]);
    }

    public function beforeGetWishlists(WishlistApi $wishlistApi, string $accountId, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::WISHLIST_BEFORE_GET_WISHLIST, [$accountId, $locale]);
    }

    public function afterGetWishlists(WishlistApi $wishlistApi, array $wishlists): ?array
    {
        return $this->callExpectMultipleObjects(HooksCallBuilder::WISHLIST_AFTER_GET_WISHLISTS, [$wishlists]);
    }

    public function beforeCreate(WishlistApi $wishlistApi, Wishlist $wishlist, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::WISHLIST_BEFORE_CREATE, [$wishlist, $locale]);
    }

    public function afterCreate(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_AFTER_CREATE, [$wishlist]);
    }

    public function beforeAddToWishlist(
        WishlistApi $wishlistApi,
        Wishlist $wishlist,
        LineItem $lineItem,
        string $locale
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::WISHLIST_BEFORE_ADD_TO_WISHLIST,
            [$wishlist, $lineItem, $locale]
        );
    }

    public function afterAddToWishlist(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_BEFORE_ADD_TO_WISHLIST, [$wishlist]);
    }

    public function beforeAddMultipleToWishlist(
        WishlistApi $wishlistApi,
        Wishlist $wishlist,
        array $lineItems,
        string $locale
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::WISHLIST_BEFORE_ADD_MULTIPLE_TO_WISHLIST,
            [$wishlist, $lineItems, $locale]
        );
    }

    public function afterAddMultipleToWishlist(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_AFTER_ADD_MULTIPLE_TO_WISHLIST, [$wishlist]);
    }

    public function beforeUpdateLineItem(
        WishlistApi $wishlistApi,
        Wishlist $wishlist,
        LineItem $lineItem,
        int $count,
        string $locale
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::WISHLIST_BEFORE_UPDATE_LINE_ITEM,
            [$wishlist, $lineItem, $count, $locale]
        );
    }

    public function afterUpdateLineItem(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_BEFORE_UPDATE_LINE_ITEM, [$wishlist]);
    }

    public function beforeRemoveLineItem(
        WishlistApi $wishlistApi,
        Wishlist $wishlist,
        LineItem $lineItem,
        string $locale
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::WISHLIST_BEFORE_UPDATE_LINE_ITEM,
            [$wishlist, $lineItem, $locale]
        );
    }

    public function afterRemoveLineItem(WishlistApi $wishlistApi, Wishlist $wishlist): ?Wishlist
    {
        return $this->callExpectObject(HooksCallBuilder::WISHLIST_AFTER_REMOVE_LINE_ITEM, [$wishlist]);
    }
}
