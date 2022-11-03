<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\ConfigCache;

class RoutingConfigCache extends ConfigCache
{
    private const HARD_EXPIRY = 120; // seconds

    private LoggerInterface $logger;

    private static ?bool $regenerateInThisRequest = null;

    public function __construct(string $file, LoggerInterface $logger, bool $debug)
    {
        parent::__construct($file, $debug);
        $this->logger = $logger;
    }

    public function isFresh()
    {
        // Symfony thinks the cache is not fresh, we agree (e.g. dev, file not exists …)
        if (!parent::isFresh()) {
            $this->logger->info('Regenerate route cache because parent indicated non fresh.', ['path' => $this->getPath()]);
            return false;
        }

        $shouldRegenerate = $this->shouldRegenerate();

        if ($shouldRegenerate) {
            $this->logger->info('Regenerating route cache to get newest routes from studio.', ['path' => $this->getPath()]);
        }
        return !$shouldRegenerate;
    }

    /**
     * Regenerate the cache latest every HARD_EXPIRY seconds, probably earlier.
     *
     * @return bool
     */
    private function shouldRegenerate(): bool
    {
        // Decide once per request if cache should be re-generated so that all files are in sync
        if (self::$regenerateInThisRequest === null) {
            $fileChangeTime = filectime($this->getPath());
            $now = microtime(true);
            // Inspired by stampede protection of Symfony cache: https://github.com/symfony/cache-contracts/blob/2eab7fa459af6d75c6463e63e633b667a9b761d3/CacheTrait.php#L58
            // This increases probability of the item being re-generated the closer we get to 2 minutes expiry
            $probableRegenerateTime = ($now - $fileChangeTime) * (1 + random_int(1, \PHP_INT_MAX) / \PHP_INT_MAX);
            self::$regenerateInThisRequest = (self::HARD_EXPIRY <= $probableRegenerateTime);
        }
        return self::$regenerateInThisRequest;
    }
}
