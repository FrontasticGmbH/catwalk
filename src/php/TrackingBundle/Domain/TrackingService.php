<?php

namespace Frontastic\Catwalk\TrackingBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CartApiBundle\Domain\Order;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

class TrackingService extends Tracker
{
    private $tracker = null;

    public function __construct(Project $project, string $configDirectory)
    {
        if (empty($project->configuration['abtesting'])) {
            return;
        }

        switch ($project->configuration['abtesting']['engine'] ?? null) {
            case 'kameleoon':
                $this->tracker = new Tracker\Kameleoon($project, $configDirectory);
                break;

            default:
                debug('Unknown tracking engine.');
        }
    }

    public function shouldRunExperiment(string $experimentId): bool
    {
        if ($this->tracker) {
            return $this->tracker->shouldRunExperiment($experimentId);
        }

        return false;
    }

    public function trackPageView(Context $context, string $pageType, ?string $path = null): void
    {
        if ($this->tracker) {
            $this->tracker->trackPageView($context, $pageType, $path);
        }
    }

    public function reachStartCheckout(Context $context): void
    {
        if ($this->tracker) {
            $this->tracker->reachStartCheckout($context);
        }
    }

    public function reachOrder(Context $context, Order $order): void
    {
        if ($this->tracker) {
            $this->tracker->reachOrder($context, $order);
        }
    }

    public function reachRegistration(Context $context, Account $account): void
    {
        if ($this->tracker) {
            $this->tracker->reachRegistration($context, $account);
        }
    }

    public function reachViewProduct(Context $context, Product $product): void
    {
        if ($this->tracker) {
            $this->tracker->reachViewProduct($context, $product);
        }
    }

    public function reachAddToBasket(Context $context, Cart $cart, LineItem $lineItem): void
    {
        if ($this->tracker) {
            $this->tracker->reachAddToBasket($context, $cart, $lineItem);
        }
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    public function flush(): void
    {
        if ($this->tracker) {
            $this->tracker->flush();
        }
    }
}
