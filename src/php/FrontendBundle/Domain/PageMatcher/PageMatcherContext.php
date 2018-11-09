<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher;

use Kore\DataObject\DataObject;

class PageMatcherContext extends DataObject
{
    /**
     * @var string|null
     */
    public $categoryId;

    /**
     * @var string|null
     */
    public $productId;

    /**
     * @var object|null
     */
    public $cart;

    /**
     * @var object|null
     */
    public $checkout;

    /**
     * @var object|null
     */
    public $checkoutFinished;

    /**
     * @var object|null
     */
    public $account;

    /**
     * @var object|null
     */
    public $accountForgotPassword;

    /**
     * @var object|null
     */
    public $accountConfirm;

    /**
     * @var object|null
     */
    public $accountProfile;

    /**
     * @var object|null
     */
    public $accountAddresses;

    /**
     * @var object|null
     */
    public $accountOrders;

    /**
     * @var object|null
     */
    public $accountWishlists;

    /**
     * @var object|null
     */
    public $accountVouchers;

    /**
     * @var object|null
     */
    public $error;

    public static function productPage($productId)
    {
        return new self(['productId' => $productId]);
    }
}
