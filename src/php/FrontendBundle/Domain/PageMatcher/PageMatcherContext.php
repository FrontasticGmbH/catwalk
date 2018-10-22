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
    public $error;

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

    public static function productPage($productId)
    {
        return new self(['productId' => $productId]);
    }
}
