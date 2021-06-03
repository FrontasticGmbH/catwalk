<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\CoreBundle\Domain\Json\Json;

class HooksCallBuilder
{
    // Product
    const PRODUCT_BEFORE_GET_CATEGORIES     = 'beforeGetCategories';
    const PRODUCT_AFTER_GET_CATEGORIES      = 'afterGetCategories';
    const PRODUCT_BEFORE_GET_PRODUCT        = 'beforeGetProduct';
    const PRODUCT_AFTER_GET_PRODUCT         = 'afterGetProduct';
    const PRODUCT_BEFORE_QUERY_CATEGORIES   = 'beforeQueryCategories';
    const PRODUCT_AFTER_QUERY_CATEGORIES    = 'afterQueryCategories';
    const PRODUCT_BEFORE_GET_PRODUCT_TYPES  = 'beforeGetProductTypes';
    const PRODUCT_AFTER_GET_PRODUCT_TYPES   = 'afterGetProductTypes';

    // Product Search
    const PRODUCT_SEARCH_BEFORE_GET_SEARCHABLE_ATTRIBUTES = 'beforeGetSearchableAttributes';
    const PRODUCT_SEARCH_AFTER_GET_SEARCHABLE_ATTRIBUTES = 'afterGetSearchableAttributes';
    const PRODUCT_SEARCH_BEFORE_QUERY              = 'beforeQueryProducts';
    const PRODUCT_SEARCH_AFTER_QUERY               = 'afterQueryProducts';


    // Wishlist
    const WISHLIST_BEFORE_GET_WISHLIST              = 'beforeGetWishlist';
    const WISHLIST_AFTER_GET_WISHLIST               = 'afterGetWishlist';
    const WISHLIST_BEFORE_GET_ANONYMOUS             = 'beforeGetAnonymous';
    const WISHLIST_AFTER_GET_ANONYMOUS              = 'afterGetAnonymous';
    const WISHLIST_BEFORE_GET_WISHLISTS             = 'beforeGetWishlists';
    const WISHLIST_AFTER_GET_WISHLISTS              = 'afterGetWishlists';
    const WISHLIST_BEFORE_CREATE                    = 'beforeCreate';
    const WISHLIST_AFTER_CREATE                     = 'afterCreate';
    const WISHLIST_BEFORE_ADD_TO_WISHLIST           = 'beforeAddToWishlist';
    const WISHLIST_AFTER_ADD_TO_WISHLIST            = 'afterAddToWishlist';
    const WISHLIST_BEFORE_ADD_MULTIPLE_TO_WISHLIST  = 'beforeAddMultipleToWishlist';
    const WISHLIST_AFTER_ADD_MULTIPLE_TO_WISHLIST   = 'afterAddMultipleToWishlist';
    const WISHLIST_BEFORE_UPDATE_LINE_ITEM          = 'beforeUpdateLineItem';
    const WISHLIST_BEFORE_GET_LINE_ITEM             = 'afterUpdateLineItem';
    const WISHLIST_BEFORE_REMOVE_LINE_ITEM          = 'beforeRemoveLineItem';
    const WISHLIST_AFTER_REMOVE_LINE_ITEM           = 'afterRemoveLineItem';

    // Cart
    const CART_BEFORE_GET_FOR_USER                     = 'beforeGetForUser';
    const CART_AFTER_GET_FOR_USER                      = 'afterGetForUser';
    const CART_BEFORE_GET_ANONYMOUS                    = 'beforeGetAnonymous';
    const CART_AFTER_GET_ANONYMOUS                     = 'afterGetAnonymous';
    const CART_BEFORE_GET_BY_ID                        = 'beforeGetById';
    const CART_AFTER_GET_BY_ID                         = 'afterGetById';
    const CART_BEFORE_ADD_TO_CART                      = 'beforeAddToCart';
    const CART_AFTER_ADD_TO_CART                       = 'afterAddToCart';
    const CART_BEFORE_UPDATE_LINE_ITEM                 = 'beforeUpdateLineItem';
    const CART_AFTER_UPDATE_LINE_ITEM                  = 'afterUpdateLineItem';
    const CART_BEFORE_REMOVE_LINE_ITEM                 = 'beforeRemoveLineItem';
    const CART_AFTER_REMOVE_LINE_ITEM                  = 'afterRemoveLineItem';
    const CART_BEFORE_SET_EMAIL                        = 'beforeSetEmail';
    const CART_AFTER_SET_EMAIL                         = 'afterSetEmail';
    const CART_BEFORE_SET_SHIPPING_METHOD              = 'beforeSetShippingMethod';
    const CART_AFTER_SET_SHIPPING_METHOD               = 'afterSetShippingMethod';
    const CART_BEFORE_SET_CUSTOM_FIELD                 = 'beforeSetCustomField';
    const CART_AFTER_SET_CUSTOM_FIELD                  = 'afterSetCustomField';
    const CART_BEFORE_SET_RAW_API_INPUT                = 'beforeSetRawApiInput';
    const CART_AFTER_SET_RAW_API_INPUT                 = 'afterSetRawApiInput';
    const CART_BEFORE_SET_SHIPPING_ADDRESS             = 'beforeSetShippingAddress';
    const CART_AFTER_SET_SHIPPING_ADDRESS              = 'afterSetShippingAddress';
    const CART_BEFORE_SET_BILLING_ADDRESS              = 'beforeSetBillingAddress';
    const CART_AFTER_SET_BILLING_ADDRESS               = 'afterSetBillingAddress';
    const CART_BEFORE_ADD_PAYMENT                      = 'beforeAddPayment';
    const CART_AFTER_ADD_PAYMENT                       = 'afterAddPayment';
    const CART_BEFORE_UPDATE_PAYMENT                   = 'beforeUpdatePayment';
    const CART_AFTER_UPDATE_PAYMENT                    = 'afterUpdatePayment';
    const CART_BEFORE_REDEEM_DISCOUNT_CODE             = 'beforeRedeemDiscountCode';
    const CART_AFTER_REDEEM_DISCOUNT_CODE              = 'afterRedeemDiscountCode';
    const CART_BEFORE_REMOVE_DISCOUNT_CODE             = 'beforeRemoveDiscountCode';
    const CART_AFTER_REMOVE_DISCOUNT_CODE              = 'afterRemoveDiscountCode';
    const CART_BEFORE_ORDER                            = 'beforeOrder';
    const CART_AFTER_ORDER                             = 'afterOrder';
    const CART_BEFORE_GET_ORDER                        = 'beforeGetOrder';
    const CART_AFTER_GET_ORDER                         = 'afterGetOrder';
    const CART_BEFORE_GET_ORDERS                       = 'beforeGetOrders';
    const CART_AFTER_GET_ORDERS                        = 'afterGetOrders';
    const CART_BEFORE_START_TRANSACTION                = 'beforeStartTransaction';
    const CART_BEFORE_COMMIT                           = 'beforeCommit';
    const CART_AFTER_COMMIT                            = 'afterCommit';
    const CART_BEFORE_GET_AVAILABLE_SHIPPING_METHODS   = 'beforeGetAvailableShippingMethods';
    const CART_AFTER_GET_AVAILABLE_SHIPPING_METHODS    = 'afterGetAvailableShippingMethods';
    const CART_BEFORE_GET_SHIPPING_METHODS             = 'beforeGetShippingMethods';
    const CART_AFTER_GET_SHIPPING_METHODS              = 'afterGetShippingMethods';

    // Account
    const ACCOUNT_BEFORE_GET_SALUTATIONS                 = 'beforeGetSalutations';
    const ACCOUNT_AFTER_GET_SALUTATIONS                  = 'afterGetSalutations';
    const ACCOUNT_BEFORE_CONFIRM_EMAIL                   = 'beforeConfirmEmail';
    const ACCOUNT_AFTER_CONFIRM_EMAIL                    = 'afterConfirmEmail';
    const ACCOUNT_BEFORE_CREATE                          = 'beforeCreate';
    const ACCOUNT_AFTER_CREATE                           = 'afterCreate';
    const ACCOUNT_BEFORE_UPDATE                          = 'beforeUpdate';
    const ACCOUNT_AFTER_UPDATE                           = 'afterUpdate';
    const ACCOUNT_BEFORE_UPDATE_PASSWORD                 = 'beforeUpdatePassword';
    const ACCOUNT_AFTER_UPDATE_PASSWORD                  = 'afterUpdatePassword';
    const ACCOUNT_BEFORE_GENERATE_PASSWORD_RESET_TOKEN   = 'beforeGeneratePasswordResetToken';
    const ACCOUNT_AFTER_GENERATE_PASSWORD_RESET_TOKEN    = 'afterGeneratePasswordResetToken';
    const ACCOUNT_BEFORE_RESET_PASSWORD                  = 'beforeResetPassword';
    const ACCOUNT_AFTER_RESET_PASSWORD                   = 'afterResetPassword';
    const ACCOUNT_BEFORE_LOGIN                           = 'beforeLogin';
    const ACCOUNT_AFTER_LOGIN                            = 'afterLogin';
    const ACCOUNT_BEFORE_REFRESH_ACCOUNT                 = 'beforeRefreshAccount';
    const ACCOUNT_AFTER_REFRESH_ACCOUNT                  = 'afterRefreshAccount';
    const ACCOUNT_BEFORE_GET_ADDRESSES                   = 'beforeGetAddresses';
    const ACCOUNT_AFTER_GET_ADDRESSES                    = 'afterGetAddresses';
    const ACCOUNT_BEFORE_ADD_ADDRESS                     = 'beforeAddAddress';
    const ACCOUNT_AFTER_ADD_ADDRESS                      = 'afterAddAddress';
    const ACCOUNT_BEFORE_UPDATE_ADDRESS                  = 'beforeUpdateAddress';
    const ACCOUNT_AFTER_UPDATE_ADDRESS                   = 'afterUpdateAddress';
    const ACCOUNT_BEFORE_REMOVE_ADDRESS                  = 'beforeRemoveAddress';
    const ACCOUNT_AFTER_REMOVE_ADDRESS                   = 'afterRemoveAddress';
    const ACCOUNT_BEFORE_SET_DEFAULT_BILLING_ADDRESS     = 'beforeSetDefaultBillingAddress';
    const ACCOUNT_AFTER_SET_DEFAULT_BILLING_ADDRESS      = 'afterSetDefaultBillingAddress';
    const ACCOUNT_BEFORE_SET_DEFAULT_SHIPPING_ADDRESS    = 'beforeSetDefaultShippingAddress';
    const ACCOUNT_AFTER_SET_DEFAULT_SHIPPING_ADDRESS     = 'afterSetDefaultShippingAddress';

    // Content
    const CONTENT_BEFORE_GET_CONTENT_TYPES  = 'beforeGetContentTypes';
    const CONTENT_AFTER_GET_CONTENT_TYPES   = 'afterGetContentTypes';
    const CONTENT_BEFORE_GET_CONTENT        = 'beforeGetContent';
    const CONTENT_AFTER_GET_CONTENT         = 'afterGetContent';
    const CONTENT_BEFORE_GET_QUERY          = 'beforeQueryContent';
    const CONTENT_AFTER_GET_QUERY           = 'afterQueryContent';

    // Master page
    const MASTER_PAGE_IDENTIFY_FROM_PRODUCT_ROUTER = 'identifyFromProductRouter';
    const MASTER_PAGE_GENERATE_URL_FOR_PRODUCT_ROUTER = 'generateUrlForProductRouter';
    const MASTER_PAGE_IDENTIFY_FROM_CATEGORY_ROUTER = 'identifyFromCategoryRouter';
    const MASTER_PAGE_GENERATE_URL_FOR_CATEGORY_ROUTER = 'generateUrlForCategoryRouter';

    const EXISTING_HOOKS = [
        self::PRODUCT_BEFORE_GET_CATEGORIES,
        self::PRODUCT_AFTER_GET_CATEGORIES,
        self::PRODUCT_BEFORE_GET_PRODUCT,
        self::PRODUCT_AFTER_GET_PRODUCT,
        self::PRODUCT_BEFORE_QUERY_CATEGORIES,
        self::PRODUCT_AFTER_QUERY_CATEGORIES,
        self::PRODUCT_BEFORE_GET_PRODUCT_TYPES,
        self::PRODUCT_AFTER_GET_PRODUCT_TYPES,

        self::PRODUCT_SEARCH_BEFORE_GET_SEARCHABLE_ATTRIBUTES,
        self::PRODUCT_SEARCH_AFTER_GET_SEARCHABLE_ATTRIBUTES,
        self::PRODUCT_SEARCH_BEFORE_QUERY,
        self::PRODUCT_SEARCH_AFTER_QUERY,

        self::WISHLIST_BEFORE_GET_WISHLIST,
        self::WISHLIST_AFTER_GET_WISHLIST,
        self::WISHLIST_BEFORE_GET_ANONYMOUS,
        self::WISHLIST_AFTER_GET_ANONYMOUS,
        self::WISHLIST_BEFORE_GET_WISHLISTS,
        self::WISHLIST_AFTER_GET_WISHLISTS,
        self::WISHLIST_BEFORE_CREATE,
        self::WISHLIST_AFTER_CREATE,
        self::WISHLIST_BEFORE_ADD_TO_WISHLIST,
        self::WISHLIST_AFTER_ADD_TO_WISHLIST,
        self::WISHLIST_BEFORE_ADD_MULTIPLE_TO_WISHLIST,
        self::WISHLIST_AFTER_ADD_MULTIPLE_TO_WISHLIST,
        self::WISHLIST_BEFORE_UPDATE_LINE_ITEM,
        self::WISHLIST_BEFORE_GET_LINE_ITEM,
        self::WISHLIST_BEFORE_REMOVE_LINE_ITEM,
        self::WISHLIST_AFTER_REMOVE_LINE_ITEM,

        self::CART_BEFORE_GET_FOR_USER,
        self::CART_AFTER_GET_FOR_USER,
        self::CART_BEFORE_GET_ANONYMOUS,
        self::CART_AFTER_GET_ANONYMOUS,
        self::CART_BEFORE_GET_BY_ID,
        self::CART_AFTER_GET_BY_ID,
        self::CART_BEFORE_ADD_TO_CART,
        self::CART_AFTER_ADD_TO_CART,
        self::CART_BEFORE_UPDATE_LINE_ITEM,
        self::CART_AFTER_UPDATE_LINE_ITEM,
        self::CART_BEFORE_REMOVE_LINE_ITEM,
        self::CART_AFTER_REMOVE_LINE_ITEM,
        self::CART_BEFORE_SET_EMAIL,
        self::CART_AFTER_SET_EMAIL,
        self::CART_BEFORE_SET_SHIPPING_METHOD,
        self::CART_AFTER_SET_SHIPPING_METHOD,
        self::CART_BEFORE_SET_CUSTOM_FIELD,
        self::CART_AFTER_SET_CUSTOM_FIELD,
        self::CART_BEFORE_SET_RAW_API_INPUT,
        self::CART_AFTER_SET_RAW_API_INPUT,
        self::CART_BEFORE_SET_SHIPPING_ADDRESS,
        self::CART_AFTER_SET_SHIPPING_ADDRESS,
        self::CART_BEFORE_SET_BILLING_ADDRESS,
        self::CART_AFTER_SET_BILLING_ADDRESS,
        self::CART_BEFORE_ADD_PAYMENT,
        self::CART_AFTER_ADD_PAYMENT,
        self::CART_BEFORE_UPDATE_PAYMENT,
        self::CART_AFTER_UPDATE_PAYMENT,
        self::CART_BEFORE_REDEEM_DISCOUNT_CODE,
        self::CART_AFTER_REDEEM_DISCOUNT_CODE,
        self::CART_BEFORE_REMOVE_DISCOUNT_CODE,
        self::CART_AFTER_REMOVE_DISCOUNT_CODE,
        self::CART_BEFORE_ORDER,
        self::CART_AFTER_ORDER,
        self::CART_BEFORE_GET_ORDER,
        self::CART_AFTER_GET_ORDER,
        self::CART_BEFORE_GET_ORDERS,
        self::CART_AFTER_GET_ORDERS,
        self::CART_BEFORE_START_TRANSACTION,
        self::CART_BEFORE_COMMIT,
        self::CART_AFTER_COMMIT,
        self::CART_BEFORE_GET_AVAILABLE_SHIPPING_METHODS,
        self::CART_AFTER_GET_AVAILABLE_SHIPPING_METHODS,
        self::CART_BEFORE_GET_SHIPPING_METHODS,
        self::CART_AFTER_GET_SHIPPING_METHODS,

        self::ACCOUNT_BEFORE_GET_SALUTATIONS,
        self::ACCOUNT_AFTER_GET_SALUTATIONS,
        self::ACCOUNT_BEFORE_CONFIRM_EMAIL,
        self::ACCOUNT_AFTER_CONFIRM_EMAIL,
        self::ACCOUNT_BEFORE_CREATE,
        self::ACCOUNT_AFTER_CREATE,
        self::ACCOUNT_BEFORE_UPDATE,
        self::ACCOUNT_AFTER_UPDATE,
        self::ACCOUNT_BEFORE_UPDATE_PASSWORD,
        self::ACCOUNT_AFTER_UPDATE_PASSWORD,
        self::ACCOUNT_BEFORE_GENERATE_PASSWORD_RESET_TOKEN,
        self::ACCOUNT_AFTER_GENERATE_PASSWORD_RESET_TOKEN,
        self::ACCOUNT_BEFORE_RESET_PASSWORD,
        self::ACCOUNT_AFTER_RESET_PASSWORD,
        self::ACCOUNT_BEFORE_LOGIN,
        self::ACCOUNT_AFTER_LOGIN,
        self::ACCOUNT_BEFORE_REFRESH_ACCOUNT,
        self::ACCOUNT_AFTER_REFRESH_ACCOUNT,
        self::ACCOUNT_BEFORE_GET_ADDRESSES,
        self::ACCOUNT_AFTER_GET_ADDRESSES,
        self::ACCOUNT_BEFORE_ADD_ADDRESS,
        self::ACCOUNT_AFTER_ADD_ADDRESS,
        self::ACCOUNT_BEFORE_UPDATE_ADDRESS,
        self::ACCOUNT_AFTER_UPDATE_ADDRESS,
        self::ACCOUNT_BEFORE_REMOVE_ADDRESS,
        self::ACCOUNT_AFTER_REMOVE_ADDRESS,
        self::ACCOUNT_BEFORE_SET_DEFAULT_BILLING_ADDRESS,
        self::ACCOUNT_AFTER_SET_DEFAULT_BILLING_ADDRESS,
        self::ACCOUNT_BEFORE_SET_DEFAULT_SHIPPING_ADDRESS,
        self::ACCOUNT_AFTER_SET_DEFAULT_SHIPPING_ADDRESS,

        self::CONTENT_BEFORE_GET_CONTENT_TYPES,
        self::CONTENT_AFTER_GET_CONTENT_TYPES,
        self::CONTENT_BEFORE_GET_CONTENT,
        self::CONTENT_AFTER_GET_CONTENT,
        self::CONTENT_BEFORE_GET_QUERY,
        self::CONTENT_AFTER_GET_QUERY,

        self::MASTER_PAGE_IDENTIFY_FROM_PRODUCT_ROUTER,
        self::MASTER_PAGE_GENERATE_URL_FOR_PRODUCT_ROUTER,
        self::MASTER_PAGE_IDENTIFY_FROM_CATEGORY_ROUTER,
        self::MASTER_PAGE_GENERATE_URL_FOR_CATEGORY_ROUTER,
    ];

    private string $name;
    private string $project;
    private Context $context;
    private array $arguments;
    private array $headers = [];
    private $serializer;

    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }

    public function name(string $name)
    {
        if (!in_array($name, self::EXISTING_HOOKS, true)) {
            throw new \InvalidArgumentException('Invalid hook name "' . $name . '"');
        }
        $this->name = $name;
    }

    public function project(string $project)
    {
        $this->project = $project;
    }

    public function context($context)
    {
        $this->context = $context;
    }

    public function arguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function header(string $key, $value)
    {
        $this->headers[$key] = $key . ':' . $value;
    }

    public function build(): HooksCall
    {
        $call = new HooksCall();
        $call->name = $this->name;
        $call->project = $this->project;
        $call->headers = $this->headers;

        $serializer = $this->serializer;
        $call->payload = Json::encode($serializer([
            'arguments' => $this->arguments,
            'context' => $this->context,
        ]));
        return $call;
    }
}
