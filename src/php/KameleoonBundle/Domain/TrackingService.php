<?php

namespace Frontastic\Catwalk\KameleoonBundle\Domain;

use Kameleoon\KameleoonClientFactory;
use Kameleoon\Data\Browser;
use Kameleoon\Data\PageView;
use Kameleoon\Data\Conversion;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CartApiBundle\Domain\Order;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

class TrackingService
{
    private $client;
    private $clientId = 'kore-frontastic-cloud';
    private $clientSecret = 'VSn7JNH_L82YUsUZtsGWr1JPMh_HnWU--G0t0kSudcA';

    private $visitorCode;

    private $userAgentMap = [
        '(opera|opr/)i' => 4, // Opera (must be before Internet Explorer)
        '(chrome)i' => 0, // Google Chrome
        '(edge|msie|trident/7)i' => 1, // Internet Explorer
        '(firefox)i' => 2, // Firefox
        '(safari)i' => 3, // Safari
    ];

    public function __construct(Project $project, string $configuration)
    {
        $kameleoonConfigFile = $this->ensureConfigFile($project, $configuration);
        $this->client = KameleoonClientFactory::create(
            $project->configuration['abtesting']['siteCode'] ?? null,
            false,
            $kameleoonConfigFile
        );

        // @TODO: Use a custom visitor code in session here, to not expose
        // Kameleoon cookie?
        $this->visitorCode = $this->client->obtainVisitorCode($_SERVER['HTTP_HOST'] ?? 'example.com');
    }

    private function ensureConfigFile(Project $project, string $configuration): string
    {
        $kameleoonConfigFile = $configuration . '/kameleoon.conf';
        if (!file_exists($kameleoonConfigFile)) {
            $kameleoonWorkingDir = sprintf(
                '/var/cache/frontastic/%s_%s/kameleoon/',
                $project->customer,
                $project->projectId
            );
            if (!file_exists($kameleoonWorkingDir)) {
                mkdir($kameleoonWorkingDir, 0755, true);
            }

            @mkdir(dirname($kameleoonConfigFile), 0755, true);
            file_put_contents(
                $kameleoonConfigFile,
                sprintf(
                    "kameleoon_work_dir = %s\nactions_configuration_refresh_interval = 60\n",
                    $kameleoonWorkingDir
                )
            );
        }

        return $kameleoonConfigFile;
    }

    public function shouldRunExperiment(string $experimentId): bool
    {
        try {
            $experiment = $this->client->triggerExperiment($this->visitorCode, $experimentId, 1000);
        } catch (\Exception $e) {
            return false;
        }

        // `reference` indicates we should run original – returns experiment ID
        // otherwise. Since we only support two-variant experiments for now
        // this results in boolean
        return $experiment !== 'reference';
    }

    public function trackPageView(Context $Context, string $pageType, ?string $path = null)
    {
        // @TODO: Track: Browser, device type, page type, actual page view
        $path = $path ?: $_SERVER['REQUEST_URI'] ?? '/';
        $this->client->addData($this->visitorCode, new Browser($this->getKameleoonBrowserId()));
        $this->client->addData($this->visitorCode, new PageView($path, $pageType, $_SERVER['HTTP_REFERER'] ?? null));
    }

    private function getKameleoonBrowserId()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'None';
        foreach ($this->userAgentMap as $expression => $browserId) {
            if (preg_match($expression, $userAgent)) {
                return $browserId;
            }
        }

        return 5; // Other
    }

    public function reachStartCheckout(Context $context)
    {
        debug('Start Checkout');
    }

    public function reachOrder(Context $context, Order $order)
    {
        // @TODO: How to know the goal ID?
        $this->client->addData($this->visitorCode, new Conversion(220195, $order->sum / 100));
    }

    public function reachRegistration(Context $context, Account $account)
    {
        debug('Registration', $account->email);
    }

    public function reachViewProduct(Context $context, Product $product)
    {
        debug('View Product', $product->sku);
    }

    public function reachAddToBasket(Context $context, Cart $cart, LineItem $lineItem)
    {
        debug('Added to cart', $lineItem->variant->sku);
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    public function flush()
    {
        $this->client->flush();
    }
}
