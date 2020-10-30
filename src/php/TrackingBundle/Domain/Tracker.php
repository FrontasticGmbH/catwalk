<?php

namespace Frontastic\Catwalk\TrackingBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CartApiBundle\Domain\Order;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

/**
 * Interface: Tracker
 *
 * @WARNING This interface is EXPERIMENTAL!
 *
 * @experimental
 */
abstract class Tracker
{
    abstract public function trackPageView(Context $context, string $pageType, ?string $path = null);

    abstract public function reachStartCheckout(Context $context): void;

    abstract public function reachOrder(Context $context, Order $order): void;

    abstract public function reachRegistration(Context $context, Account $account): void;

    abstract public function reachViewProduct(Context $context, Product $product): void;

    abstract public function reachAddToBasket(Context $context, Cart $cart, LineItem $lineItem): void;

    public function shouldRunExperiment(string $experimentId): bool
    {
        return false;
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    abstract public function flush(): void;
}
