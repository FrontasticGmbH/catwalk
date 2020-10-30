<?php

namespace Frontastic\Catwalk\TrackingBundle\Domain\Tracker;

use Kameleoon\KameleoonClientFactory;
use Kameleoon\Data\Browser;
use Kameleoon\Data\PageView;
use Kameleoon\Data\Conversion;

use Frontastic\Catwalk\TrackingBundle\Domain\Tracker;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CartApiBundle\Domain\LineItem;
use Frontastic\Common\CartApiBundle\Domain\Order;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

class Kameleoon extends Tracker
{
    private $client;
    private $clientId;
    private $clientSecret;
    private $goal;

    private $visitorCode;

    private $userAgentMap = [
        '(opera|opr/)i' => 4, // Opera (must be before Internet Explorer)
        '(chrome)i' => 0, // Google Chrome
        '(edge|msie|trident/7)i' => 1, // Internet Explorer
        '(firefox)i' => 2, // Firefox
        '(safari)i' => 3, // Safari
    ];

    public function __construct(Project $project, string $configDirectory)
    {
        $trackingConfigFile = $this->ensureConfigFile($project, $configDirectory);
        debug($trackingConfigFile);
        $this->client = KameleoonClientFactory::create(
            $project->configuration['abtesting']['siteCode'] ?? null,
            false,
            $trackingConfigFile
        );
        $this->clientId = $project->configuration['abtesting']['clientId'] ?? null;
        $this->clientSecret = $project->configuration['abtesting']['clientSecret'] ?? null;
        $this->goal = $project->configuration['abtesting']['mainGoal'] ?? null;

        // @TODO: Use a custom visitor code in session here, to not expose
        // Tracking cookie?
        $this->visitorCode = $this->client->obtainVisitorCode($_SERVER['HTTP_HOST'] ?? 'example.com');
    }

    private function ensureConfigFile(Project $project, string $configDirectory): string
    {
        $trackingConfigFile = $configDirectory . '/tracking.conf';
        $trackingWorkingDir = sprintf(
            '/var/cache/frontastic/%s_%s/tracking/',
            $project->customer,
            $project->projectId
        );

        if (!file_exists($trackingConfigFile) || !file_exists($trackingWorkingDir)) {
            @mkdir($trackingWorkingDir, 0755, true);
            @mkdir(dirname($trackingConfigFile), 0755, true);

            file_put_contents(
                $trackingConfigFile,
                sprintf(
                    "kameleoon_work_dir = %s\nactions_configuration_refresh_interval = 60\n",
                    $trackingWorkingDir
                )
            );
        }

        return $trackingConfigFile;
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

    public function trackPageView(Context $context, string $pageType, ?string $path = null): void
    {
        $path = $path ?: $_SERVER['REQUEST_URI'] ?? '/';
        debug($this->visitorCode, $this->getTrackingBrowserId());
        debug($this->visitorCode, $path, $pageType, $_SERVER['HTTP_REFERER'] ?? null);
        $this->client->addData($this->visitorCode, new Browser($this->getTrackingBrowserId()));
        $this->client->addData($this->visitorCode, new PageView($path, $pageType, $_SERVER['HTTP_REFERER'] ?? null));
    }

    private function getTrackingBrowserId(): int
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'None';
        foreach ($this->userAgentMap as $expression => $browserId) {
            if (preg_match($expression, $userAgent)) {
                return $browserId;
            }
        }

        return 5; // Other
    }

    public function reachStartCheckout(Context $context): void
    {
        debug('Start Checkout');
    }

    public function reachOrder(Context $context, Order $order): void
    {
        $this->client->addData($this->visitorCode, new Conversion($this->goal, $order->sum / 100));
    }

    public function reachRegistration(Context $context, Account $account): void
    {
        debug('Registration', $account->email);
    }

    public function reachViewProduct(Context $context, Product $product): void
    {
        debug('View Product', $product->sku);
    }

    public function reachAddToBasket(Context $context, Cart $cart, LineItem $lineItem): void
    {
        debug('Added to cart', $lineItem->variant->sku);
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    public function flush(): void
    {
        $this->client->flush($this->visitorCode);
    }
}
