<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/version' => [[['_route' => 'Frontastic.Core.System.version', '_controller' => 'Frontastic\\Common\\CoreBundle\\Controller\\SystemController::versionAction'], null, ['GET' => 0], null, false, false, null]],
        '/tree' => [[['_route' => 'Frontastic.Frontend.Node.tree', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\NodeController::treeAction'], null, ['GET' => 0], null, false, false, null]],
        '/categories' => [[['_route' => 'Frontastic.Frontend.Category.all', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CategoryController::allAction'], null, ['GET' => 0], null, false, false, null]],
        '/_recordFrontendError' => [[['_route' => 'Frontastic.Frontend.Master.Error.recordFrontendError', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ErrorController::recordFrontendErrorAction'], null, ['POST' => 0], null, false, false, null]],
        '/__error' => [[['_route' => 'Frontastic.Frontend.Master.Error.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ErrorController::errorAction'], null, ['GET' => 0], null, false, false, null]],
        '/checkout/cart' => [[['_route' => 'Frontastic.Frontend.Master.Checkout.cart', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CheckoutController::cartAction'], null, ['GET' => 0], null, false, false, null]],
        '/checkout/checkout' => [[['_route' => 'Frontastic.Frontend.Master.Checkout.checkout', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CheckoutController::checkoutAction'], null, ['GET' => 0], null, false, false, null]],
        '/checkout/finished' => [[['_route' => 'Frontastic.Frontend.Master.Checkout.finished', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CheckoutController::finishedAction'], null, ['GET' => 0], null, false, false, null]],
        '/account' => [[['_route' => 'Frontastic.Frontend.Master.Account.index', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::indexAction'], null, ['GET' => 0], null, false, false, null]],
        '/account/profile' => [[['_route' => 'Frontastic.Frontend.Master.Account.profile', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::profileAction'], null, ['GET' => 0], null, false, false, null]],
        '/account/addresses' => [[['_route' => 'Frontastic.Frontend.Master.Account.addresses', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::addressesAction'], null, ['GET' => 0], null, false, false, null]],
        '/account/orders' => [[['_route' => 'Frontastic.Frontend.Master.Account.orders', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::ordersAction'], null, ['GET' => 0], null, false, false, null]],
        '/account/wishlists' => [[['_route' => 'Frontastic.Frontend.Master.Account.wishlists', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::wishlistsAction'], null, ['GET' => 0], null, false, false, null]],
        '/account/vouchers' => [[['_route' => 'Frontastic.Frontend.Master.Account.vouchers', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::vouchersAction'], null, ['GET' => 0], null, false, false, null]],
        '/preview' => [[['_route' => 'Frontastic.Frontend.Preview.store', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PreviewController::storeAction'], null, ['POST' => 0], null, false, false, null]],
        '/tastic' => [[['_route' => 'Frontastic.Frontend.Tastic.all', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\TasticController::allAction'], null, ['GET' => 0], null, false, false, null]],
        '/facet' => [[['_route' => 'Frontastic.Frontend.Facet.all', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\FacetController::allAction'], null, ['GET' => 0], null, false, false, null]],
        '/proxy' => [[['_route' => 'Frontastic.Frontend.Proxy', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ProxyController::indexAction'], null, ['GET' => 0], null, false, false, null]],
        '/_patterns' => [[['_route' => 'Frontastic.Frontend.PatternLibrary.overview', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PatternLibraryController::indexAction'], null, ['GET' => 0], null, false, false, null]],
        '/_patterns/show' => [[['_route' => 'Frontastic.Frontend.PatternLibrary.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PatternLibraryController::indexAction'], null, ['GET' => 0], null, false, false, null]],
        '/api/cart/cart' => [[['_route' => 'Frontastic.CartApi.Cart.get', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::getAction'], null, null, null, false, false, null]],
        '/api/cart/cart/add' => [[['_route' => 'Frontastic.CartApi.Cart.add', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::addAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/addMultiple' => [[['_route' => 'Frontastic.CartApi.Cart.addMultiple', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::addMultipleAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/lineItem' => [[['_route' => 'Frontastic.CartApi.Cart.updateLineItem', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::updateLineItemAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/lineItem/remove' => [[['_route' => 'Frontastic.CartApi.Cart.removeLineItem', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::removeLineItemAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/discount-remove' => [[['_route' => 'Frontastic.CartApi.Cart.removeDiscount', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::removeDiscountAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/update' => [[['_route' => 'Frontastic.CartApi.Cart.update', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::updateAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/cart/checkout' => [[['_route' => 'Frontastic.CartApi.Cart.checkout', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::checkoutAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.get', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::getAction'], null, null, null, false, false, null]],
        '/api/cart/wishlist/create' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.create', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::createAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist/add' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.add', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::addAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist/addMultiple' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.addMultiple', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::addMultipleAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist/lineItem' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.updateLineItem', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::updateLineItemAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist/lineItem/remove' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.removeLineItem', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::removeLineItemAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/cart/wishlist/checkout' => [[['_route' => 'Frontastic.WishlistApi.Wishlist.checkout', '_controller' => 'Frontastic\\Common\\WishlistApiBundle\\Controller\\WishlistController::checkoutAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/product/categories' => [[['_route' => 'Frontastic.ProductApi.Api.categories', '_controller' => 'Frontastic\\Common\\ProductApiBundle\\Controller\\CategoryController::listAction'], null, null, null, false, false, null]],
        '/api/product/productTypes' => [[['_route' => 'Frontastic.ProductApi.Api.productTypes', '_controller' => 'Frontastic\\Common\\ProductApiBundle\\Controller\\ProductTypeController::listAction'], null, null, null, false, false, null]],
        '/api/product/search' => [[['_route' => 'Frontastic.ProductApi.Api.search', '_controller' => 'Frontastic\\Common\\ProductApiBundle\\Controller\\SearchController::listAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/project/searchableAttributes' => [[['_route' => 'Frontastic.ProjectApi.Api.searchableAttributes', '_controller' => 'Frontastic\\Common\\ProjectApiBundle\\Controller\\AttributesController::searchableAttributesAction'], null, null, null, false, false, null]],
        '/api/contents/contentTypes' => [[['_route' => 'Frontastic.ContentApi.Api.contentTypes', '_controller' => 'Frontastic\\Common\\ContentApiBundle\\Controller\\ContentTypeController::listAction'], null, null, null, false, false, null]],
        '/api/contents/get' => [[['_route' => 'Frontastic.ContentApi.Api.get', '_controller' => 'Frontastic\\Common\\ContentApiBundle\\Controller\\SearchController::showAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/contents/search' => [[['_route' => 'Frontastic.ContentApi.Api.search', '_controller' => 'Frontastic\\Common\\ContentApiBundle\\Controller\\SearchController::listAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/contents/contentId' => [[['_route' => 'Frontastic.ContentApi.Api.contentId', '_controller' => 'Frontastic\\Common\\ContentApiBundle\\Controller\\ContentIdController::listAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account' => [[['_route' => 'Frontastic.AccountApi.Api.session', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::indexAction'], null, null, null, true, false, null]],
        '/api/account/login' => [[['_route' => 'Frontastic.AccountApi.Api.login', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::indexAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/logout' => [[['_route' => 'Frontastic.AccountApi.Api.logout', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::logoutAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/register' => [[['_route' => 'Frontastic.AccountApi.Api.register', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::registerAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/resendinvitation' => [[['_route' => 'Frontastic.AccountApi.Api.resendInvitation', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::resendInvitationEmail'], null, ['PUT' => 0], null, false, false, null]],
        '/api/account/request' => [[['_route' => 'Frontastic.AccountApi.Api.requestReset', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::requestResetAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/password' => [[['_route' => 'Frontastic.AccountApi.Api.changePassword', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::changePasswordAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/update' => [[['_route' => 'Frontastic.AccountApi.Api.update', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::updateAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/address/new' => [[['_route' => 'Frontastic.AccountApi.Api.addAddress', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountApiController::addAddressAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/address/update' => [[['_route' => 'Frontastic.AccountApi.Api.updateAddress', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountApiController::updateAddressAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/address/remove' => [[['_route' => 'Frontastic.AccountApi.Api.removeAddress', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountApiController::removeAddressAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/address/setDefaultBilling' => [[['_route' => 'Frontastic.AccountApi.Api.setDefaultBillingAddress', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountApiController::setDefaultBillingAddressAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/account/address/setDefaultShipping' => [[['_route' => 'Frontastic.AccountApi.Api.setDefaultShippingAddress', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountApiController::setDefaultShippingAddressAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/context' => [[['_route' => 'Frontastic.ApiCoreBundle.Api.context', '_controller' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ApiController::contextAction'], null, ['GET' => 0], null, false, false, null]],
        '/api/endpoint' => [[['_route' => 'Frontastic.ApiCoreBundle.Api.endpoint', '_controller' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ApiController::endpointAction'], null, ['POST' => 0], null, false, false, null]],
        '/api/endpoint/version' => [[['_route' => 'Frontastic.ApiCoreBundle.Api.version', '_controller' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\ApiController::versionAction'], null, ['GET' => 0], null, false, false, null]],
        '/devvm/sync' => [[['_route' => 'Frontastic.DevVmBundle.sync', '_controller' => 'Frontastic\\Catwalk\\DevVmBundle\\Controller\\SyncController::syncAction'], null, ['POST' => 0], null, false, false, null]],
        '/devvm/tunnel' => [[['_route' => 'Frontastic.DevVmBundle.Ngrok.tunnels', '_controller' => 'Frontastic\\Catwalk\\DevVmBundle\\Controller\\NgrokController::tunnelAction'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/node/([^/]++)(*:21)'
                .'|/(.+)/p/([^/]++)(*:44)'
                .'|/(.+)/z/([^/]++)(*:67)'
                .'|/q/([^/]++)(*:85)'
                .'|/c/([^/]++)/([^/]++)(*:112)'
                .'|/a(?'
                    .'|ccount/(?'
                        .'|forgotPassword/([^/]++)(*:158)'
                        .'|confirm/([^/]++)(*:182)'
                    .')'
                    .'|pi/(?'
                        .'|cart/(?'
                            .'|order/([^/]++)(*:219)'
                            .'|cart/discount/([^/]++)(*:249)'
                        .')'
                        .'|account/(?'
                            .'|confirm/([^/]++)(*:285)'
                            .'|reset/([^/]++)(*:307)'
                        .')'
                        .'|([^/]++)/(?'
                            .'|data(*:332)'
                            .'|([^/]++)(*:348)'
                        .')'
                    .')'
                .')'
                .'|/p/([^/]++)(*:370)'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        21 => [[['_route' => 'Frontastic.Frontend.Node.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\NodeController::viewAction'], ['nodeId'], ['GET' => 0], null, false, true, null]],
        44 => [[['_route' => 'Frontastic.Frontend.Master.Product.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ProductController::viewAction'], ['url', 'identifier'], ['GET' => 0], null, false, true, null]],
        67 => [[['_route' => 'Frontastic.Frontend.Master.Content.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ContentController::viewAction'], ['url', 'identifier'], ['GET' => 0], null, false, true, null]],
        85 => [[['_route' => 'Frontastic.Frontend.Master.Search.search', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\SearchController::searchAction'], ['phrase'], ['GET' => 0], null, false, true, null]],
        112 => [[['_route' => 'Frontastic.Frontend.Master.Category.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CategoryController::viewAction'], ['id', 'slug'], ['GET' => 0], null, false, true, null]],
        158 => [[['_route' => 'Frontastic.Frontend.Master.Account.forgotPassword', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::forgotPasswordAction'], ['confirmationToken'], ['GET' => 0], null, false, true, null]],
        182 => [[['_route' => 'Frontastic.Frontend.Master.Account.confirm', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\AccountController::confirmAction'], ['confirmationToken'], ['GET' => 0], null, false, true, null]],
        219 => [[['_route' => 'Frontastic.CartApi.Cart.getOrder', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::getOrderAction'], ['order'], null, null, false, true, null]],
        249 => [[['_route' => 'Frontastic.CartApi.Cart.redeemDiscount', '_controller' => 'Frontastic\\Common\\CartApiBundle\\Controller\\CartController::redeemDiscountAction'], ['code'], ['POST' => 0], null, false, true, null]],
        285 => [[['_route' => 'Frontastic.AccountApi.Api.confirm', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::confirmAction'], ['confirmationToken'], ['POST' => 0], null, false, true, null]],
        307 => [[['_route' => 'Frontastic.AccountApi.Api.reset', '_controller' => 'Frontastic\\Common\\AccountApiBundle\\Controller\\AccountAuthController::resetAction'], ['token'], ['POST' => 0], null, false, true, null]],
        332 => [[['_route' => 'Frontastic.ApiCoreBundle.App.data', '_controller' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\AppController::dataAction'], ['app'], ['GET' => 0], null, false, false, null]],
        348 => [[['_route' => 'Frontastic.ApiCoreBundle.App.get', '_controller' => 'Frontastic\\Catwalk\\ApiCoreBundle\\Controller\\AppController::getAction'], ['app', 'dataId'], ['GET' => 0], null, false, true, null]],
        370 => [
            [['_route' => 'Frontastic.Frontend.Preview.view', '_controller' => 'Frontastic\\Catwalk\\FrontendBundle\\Controller\\PreviewController::viewAction'], ['preview'], ['GET' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
