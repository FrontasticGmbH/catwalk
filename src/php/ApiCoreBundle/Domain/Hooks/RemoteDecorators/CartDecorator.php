<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\CartApi;
use Frontastic\Common\CartApiBundle\Domain\CartApi\LifecycleEventDecorator\BaseImplementationV2;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CartApiBundle\Domain\Order;
use Frontastic\Common\CartApiBundle\Domain\Payment;

class CartDecorator extends BaseImplementationV2
{
    use DecoratorCallTrait;

    public function beforeGetForUser(CartApi $cartApi, Account $account, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_GET_FOR_USER, [$account, $locale]);
    }

    public function afterGetForUser(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_FOR_USER, [$cart]);
    }

    public function beforeGetAnonymous(CartApi $cartApi, string $anonymousId, string $locale): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_GET_ANONYMOUS, [$anonymousId, $locale]);
    }

    public function afterGetAnonymous(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_ANONYMOUS, [$cart]);
    }

    public function beforeGetById(CartApi $cartApi, string $cartId, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_GET_BY_ID, [$cartId, $locale]);
    }

    public function afterGetById(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_BY_ID, [$cart]);
    }

    public function beforeAddToCart(CartApi $cartApi, Cart $cart, LineItem $lineItem, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_ADD_TO_CART, [$cart, $lineItem, $locale]);
    }

    public function afterAddToCart(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_ADD_TO_CART, [$cart]);
    }

    public function beforeUpdateLineItem(
        CartApi $cartApi,
        Cart $cart,
        LineItem $lineItem,
        int $count,
        ?array $custom = null,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_UPDATE_LINE_ITEM,
            [$cart, $lineItem, $count, $custom, $locale]
        );
    }

    public function afterUpdateLineItem(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_UPDATE_LINE_ITEM, [$cart]);
    }

    public function beforeRemoveLineItem(
        CartApi $cartApi,
        Cart $cart,
        LineItem $lineItem,
        string $locale = null
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_REMOVE_LINE_ITEM, [$cart, $lineItem, $locale]);
    }

    public function afterRemoveLineItem(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_REMOVE_LINE_ITEM, [$cart]);
    }

    public function beforeSetEmail(CartApi $cartApi, Cart $cart, string $email, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_SET_EMAIL, [$cart, $email, $locale]);
    }

    public function afterSetEmail(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_EMAIL, [$cart]);
    }

    public function beforeSetShippingMethod(
        CartApi $cartApi,
        Cart $cart,
        string $shippingMethod,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_SET_SHIPPING_METHOD,
            [$cart, $shippingMethod, $locale]
        );
    }

    public function afterSetShippingMethod(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_SHIPPING_METHOD, [$cart]);
    }

    public function beforeSetCustomField(CartApi $cartApi, Cart $cart, array $fields, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_SET_CUSTOM_FIELD, [$cart, $fields, $locale]);
    }

    public function afterSetCustomField(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_CUSTOM_FIELD, [$cart]);
    }

    public function beforeSetRawApiInput(CartApi $cartApi, Cart $cart, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_SET_RAW_API_INPUT, [$cart, $locale]);
    }

    public function afterSetRawApiInput(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_RAW_API_INPUT, [$cart]);
    }

    public function beforeSetShippingAddress(
        CartApi $cartApi,
        Cart $cart,
        Address $address,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_SET_SHIPPING_ADDRESS,
            [$cart, $address, $locale]
        );
    }

    public function afterSetShippingAddress(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_SHIPPING_ADDRESS, [$cart]);
    }

    public function beforeSetBillingAddress(
        CartApi $cartApi,
        Cart $cart,
        Address $address,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_SET_BILLING_ADDRESS,
            [$cart, $address, $locale]
        );
    }

    public function afterSetBillingAddress(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_SET_BILLING_ADDRESS, [$cart]);
    }

    public function beforeAddPayment(
        CartApi $cartApi,
        Cart $cart,
        Payment $payment,
        ?array $custom = null,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_ADD_PAYMENT,
            [$cart, $payment, $custom, $locale]
        );
    }

    public function afterAddPayment(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_ADD_PAYMENT, [$cart]);
    }

    public function beforeUpdatePayment(CartApi $cartApi, Cart $cart, Payment $payment, string $localeString): ?array
    {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_UPDATE_LINE_ITEM,
            [$cart, $payment, $localeString]
        );
    }

    public function afterUpdatePayment(CartApi $cartApi, Payment $payment): ?Payment
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_UPDATE_LINE_ITEM, [$payment]);
    }

    public function beforeRedeemDiscountCode(CartApi $cartApi, Cart $cart, string $code, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_REDEEM_DISCOUNT_CODE, [$cart, $code, $locale]);
    }

    public function afterRedeemDiscountCode(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_REDEEM_DISCOUNT_CODE, [$cart]);
    }

    public function beforeRemoveDiscountCode(
        CartApi $cartApi,
        Cart $cart,
        LineItem $discountLineItem,
        string $locale = null
    ): ?array {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_REMOVE_DISCOUNT_CODE,
            [$cart, $discountLineItem, $locale]
        );
    }

    public function afterRemoveDiscountCode(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_REMOVE_DISCOUNT_CODE, [$cart]);
    }

    public function beforeOrder(CartApi $cartApi, Cart $cart, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_ORDER, [$cart, $locale]);
    }

    public function afterOrder(CartApi $cartApi, Order $order): ?Order
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_ORDER, [$order]);
    }

    public function beforeGetOrder(CartApi $cartApi, Account $account, string $orderId, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_GET_ORDER, [$account, $orderId, $locale]);
    }

    public function afterGetOrder(CartApi $cartApi, Order $order): ?Order
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_ORDER, [$order]);
    }

    public function beforeGetOrders(CartApi $cartApi, Account $account, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_GET_ORDERS, [$account, $locale]);
    }

    public function afterGetOrders(CartApi $cartApi, array $orders): ?array
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_ORDERS, [$orders]);
    }

    public function beforeStartTransaction(CartApi $cartApi, Cart $cart): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_START_TRANSACTION, [$cart]);
    }

    public function beforeCommit(CartApi $cartApi, string $locale = null): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CART_BEFORE_COMMIT, [$locale]);
    }

    public function afterCommit(CartApi $cartApi, Cart $cart): ?Cart
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_COMMIT, [$cart]);
    }

    public function beforeGetAvailableShippingMethods(CartApi $cartApi, Cart $cart, string $localeString): ?array
    {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_GET_AVAILABLE_SHIPPING_METHODS,
            [$cart, $localeString]
        );
    }

    public function afterGetAvailableShippingMethods(CartApi $cartApi, array $availableShippingMethods): ?array
    {
        return $this->callExpectObject(
            HooksCallBuilder::CART_AFTER_GET_AVAILABLE_SHIPPING_METHODS,
            [$availableShippingMethods]
        );
    }

    public function beforeGetShippingMethods(CartApi $cartApi, string $localeString, bool $onlyMatching = false): ?array
    {
        return $this->callExpectList(
            HooksCallBuilder::CART_BEFORE_GET_SHIPPING_METHODS,
            [$localeString, $onlyMatching]
        );
    }

    public function afterGetShippingMethods(CartApi $cartApi, array $shippingMethods): ?array
    {
        return $this->callExpectObject(HooksCallBuilder::CART_AFTER_GET_SHIPPING_METHODS, [$shippingMethods]);
    }
}
