<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Monolog;

use Monolog\Handler\HandlerWrapper;
use Monolog\Handler\FilterHandler;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Environment;

/**
 * This filter is used to filter out the route logging from the symfony router
 * in production and staging environment.
 */
class RouteLoggingFilter extends HandlerWrapper
{
    private string $environment;

    public function __construct(
        FilterHandler $handler,
        string $environment
    ) {
        parent::__construct($handler);
        $this->environment = $environment;
    }

    public function isHandling(array $record)
    {
        if ($this->shouldFilter($record)) {
            return false;
        }

        return $this->handler->isHandling($record);
    }

    public function handle(array $record)
    {
        if (!$this->isHandling($record)) {
            return false;
        }

        return $this->handler->handle($record);
    }

    public function handleBatch(array $records)
    {
        foreach ($records as $record) {
            $this->handle($record);
        }
    }

    private function shouldFilter(array $record)
    {
        // do not filter in development or testing environment
        if ($this->environment === Environment::DEVELOPMENT ||
            $this->environment === Environment::TESTING) {
            return false;
        }

        // do not filter logs without message
        if (!isset($record['message'])) {
            return false;
        }

        // filter if message starts with 'Matched route'
        $messageStart = 'Matched route';
        return substr($record['message'], 0, strlen($messageStart)) === $messageStart;
    }
}
