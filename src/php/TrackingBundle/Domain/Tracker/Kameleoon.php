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
    /**
     * @var string
     */
    private $client;

    /**
     * @var int[]
     */
    private $goals = [];

    /**
     * @var string
     */
    private $visitorCode;

    /**
     * @var [string => int]
     */
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

        $this->client = KameleoonClientFactory::create(
            $project->configuration['abtesting']['siteCode'] ?? null,
            false,
            $trackingConfigFile,
            $project->configuration['abtesting']['clientId'] ?? null,
            $project->configuration['abtesting']['clientSecret'] ?? null
        );

        $this->goals = [];
        if (!empty($project->configuration['abtesting']['goals'])) {
            $this->goals = (object) $project->configuration['abtesting']['goals'];
        } elseif (!empty($project->configuration['abtesting']['mainGoal'])) {
            // Deprecated configuration option
            $this->goals = (object) [
                'order' => $project->configuration['abtesting']['mainGoal'],
            ];
        }

        // @TODO: Use a custom visitor code in session here, to not expose
        // Tracking cookie?
        $this->visitorCode = $this->client->obtainVisitorCode($_SERVER['HTTP_HOST'] ?? 'example.com');
    }

    private function ensureConfigFile(Project $project, string $configDirectory): string
    {
        $trackingConfigFile = $configDirectory . '/tracking.json';
        $trackingWorkingDir = sprintf(
            '/var/cache/frontastic/%s_%s/tracking/',
            $project->customer,
            $project->projectId
        );

        if (!file_exists($trackingConfigFile) || !file_exists($trackingWorkingDir)) {
            file_put_contents(
                $trackingConfigFile,
                json_encode([
                    'kameleoon_work_dir' => $trackingWorkingDir,
                    'actions_configuration_refresh_interval' => 60,
                ])
            );
        }

        return $trackingConfigFile;
    }

    public function shouldRunExperiment(string $experimentId): bool
    {
        try {
            $runVariation = $this->client->triggerExperiment($this->visitorCode, $experimentId, 1000);
        } catch (\Exception $e) {
            return false;
        }

        // `0` indicates we should run original – returns experiment ID
        // otherwise. Since we only support two-variant experiments for now
        // this results in boolean
        return (bool) $runVariation;
    }

    public function trackPageView(Context $context, string $pageType, ?string $path = null): void
    {
        $path = $path ?: $_SERVER['REQUEST_URI'] ?? '/';
        debug($this->visitorCode, $this->getTrackingBrowserId());
        debug($this->visitorCode, $path, $pageType, $_SERVER['HTTP_REFERER'] ?? null);
        $this->client->addData($this->visitorCode, new Browser($this->getTrackingBrowserId()));
        $this->client->addData(
            $this->visitorCode,
            new PageView(
                $path,
                $pageType,
                preg_replace('([^A-Za-z0-9:/?&_=% -]+)', '', $_SERVER['HTTP_REFERER'] ?? '')
            )
        );
    }

    public function reachOrder(Context $context, Order $order): void
    {
        foreach ($this->goals->order ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal, $order->sum / 100, false));
        }
    }

    public function reachViewProduct(Context $context): void
    {
        foreach ($this->goals->productDetailPage ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachViewProductListing(Context $context): void
    {
        foreach ($this->goals->productListingPage ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachAddToBasket(Context $context, Cart $cart, LineItem $lineItem): void
    {
        foreach ($this->goals->addToCart ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachStartCheckout(Context $context): void
    {
        foreach ($this->goals->checkoutPage ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachPaymentPage(Context $context): void
    {
        foreach ($this->goals->paymentPage ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachLogin(Context $context, Account $account): void
    {
        foreach ($this->goals->login ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    public function reachRegistration(Context $context, Account $account): void
    {
        foreach ($this->goals->registration ?? [] as $goal) {
            $this->client->addData($this->visitorCode, new Conversion($goal));
        }
    }

    /**
     * Only call this on kernel.terminate to now cause any negative performance
     * impact on page delivery.
     */
    public function flush(): void
    {
        $this->client->flush($this->visitorCode);
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
}
