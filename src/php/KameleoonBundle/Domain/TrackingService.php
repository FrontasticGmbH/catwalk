<?php

namespace Frontastic\Catwalk\KameleoonBundle\Domain;

class TrackingService
{
    private $client;
    private $clientId = 'kore-frontastic-cloud';
    private $clientSecret = 'VSn7JNH_L82YUsUZtsGWr1JPMh_HnWU--G0t0kSudcA';

    public function __construct()
    {
    }

    public function trackPageView(Context $Context, string $pageType, ?string $path = null)
    {
        // @TODO: Track: Browser, device type, page type, actual page view
        $path = $path ?: $_SERVER['REQUEST_URI'];
        debug('Page View', $pageType, $path);
    }

    public function trackOrder(Context $context, Order $order)
    {
        debug('Order', $order->sum);
    }

    public function trackRegistration(Context $context, Account $account)
    {
        debug('Registration', $account->email);
    }

    public function trackAddToBasket(Context $context, Cart $cart, Product $product)
    {
        debug('Added', $product->sku);
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    public function flush()
    {
        return null;
    }
}
