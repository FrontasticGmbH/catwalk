<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\HttpClient;
use Frontastic\Common\HttpClient\Response;
use Psr\Log\LoggerInterface;

class HooksApiClient
{
    const BASE_PATH = 'http://localhost:8082/'; // TODO: move to a config file later on

    const DEFAULT_HEADERS = ['Content-Type: application/json'];

    /**
     * @var HttpClient
     */
    private HttpClient $httpClient;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param HttpClient $httpClient
     * @param LoggerInterface $logger
     */
    public function __construct(HttpClient $httpClient, LoggerInterface $logger)
    {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    public function getHooks(string $project): array
    {
        $response = $this->httpClient->get($this->makePath('hooks', $project));

        if ($response->status != 200) {
            throw new \Exception(
                'Fetching available hooks failed. Error: ' . $response->body
            );
        }

        return Json::decode($response->body);
    }

    /**
     * @param HooksCall $call
     * @return string
     * @throws \Exception
     */
    public function callEvent(HooksCall $call): string
    {
        $start = microtime(true);

        $response = $this->httpClient->post(
            $this->makePath('run', $call->getProject(), $call->getName()),
            $call->getPayload(),
            $call->headers + self::DEFAULT_HEADERS
        );

        $this->log($start, $call, $response);

        if ($response->status != 200) {
            throw new \Exception('Calling hook ' . $call->getName() . ' failed. Error: ' . $response->body);
        }

        return $response->body;
    }

    private function makePath(string ...$uri): string
    {
        return self::BASE_PATH . implode("/", $uri);
    }

    private function log(float $startTime, $call, Response $response)
    {
        $time = microtime(true) - $startTime;

        $this->logger->info(
            sprintf(
                'Request for %s took %dms',
                $call->getName(),
                $time * 1000
            ),
            [
                'hookEvent' => [
                    'event' => $call->getName(),
                    'responseTime' => $time,
                    'statusCode' => $response->status,
                ],
            ]
        );
    }
}
