<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing;

use Frontastic\Catwalk\ApiCoreBundle\Domain\RandomValueProvider;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TimeProvider;
use Psr\Log\LoggerInterface;
use Symfony\Component\Config\ConfigCacheFactoryInterface;

class RoutingConfigCacheFactory implements ConfigCacheFactoryInterface
{
    private LoggerInterface $logger;
    /**
     * @var false
     */
    private bool $debug;

    public function __construct(LoggerInterface $logger, $debug = false)
    {
        $this->logger = $logger;
        $this->debug = $debug;
    }

    public function cache($file, $callback)
    {
        if (!\is_callable($callback)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid type for callback argument. Expected callable, but got "%s".',
                    \gettype($callback)
                )
            );
        }

        $this->logger->debug('Checking custom route cache');

        $cache = new RoutingConfigCache(
            $file,
            $this->logger,
            new TimeProvider(),
            new RandomValueProvider(),
            $this->debug
        );
        if (!$cache->isFresh()) {
            $callback($cache);
        }

        return $cache;
    }
}
