<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher;

use Kore\DataObject\DataObject;

/**
 * @SuppressWarnings(PHPMD.TooManyFields) FIXME: Refactor
 */
class PageMatcherContext extends DataObject
{
    /**
     * @var ?object
     */
    public $entity;

    /**
     * @var ?string
     */
    public $categoryId;

    /**
     * @var ?string
     */
    public $productId;

    /**
     * @var ?string
     */
    public $contentId;

    /**
     * @var ?string
     */
    public $search;

    /**
     * @var ?object
     */
    public $cart;

    /**
     * @var ?object
     */
    public $checkout;

    /**
     * @var ?object
     */
    public $checkoutFinished;

    /**
     * @var ?string
     */
    public $orderId;

    /**
     * @var ?object
     */
    public $account;

    /**
     * @var ?object
     */
    public $accountForgotPassword;

    /**
     * @var ?object
     */
    public $accountConfirm;

    /**
     * @var ?object
     */
    public $accountProfile;

    /**
     * @var ?object
     */
    public $accountAddresses;

    /**
     * @var ?object
     */
    public $accountOrders;

    /**
     * @var ?object
     */
    public $accountWishlists;

    /**
     * @var ?object
     */
    public $accountVouchers;

    /**
     * @var ?object
     */
    public $error;

    public static function productPage($productId)
    {
        return new self(['productId' => $productId]);
    }
}
