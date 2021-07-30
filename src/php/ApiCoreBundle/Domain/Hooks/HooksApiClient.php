<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Frontastic\Common\HttpClient;

class HooksApiClient
{
    const BASE_PATH = 'http://localhost:8082/'; // TODO: move to a config file later on

    const DEFAULT_HEADERS = ['Content-Type: application/json'];

    private HttpClient $httpClient;
    private LoggerInterface $logger;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
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
        $response = $this->httpClient->post(
            $this->makePath('run', $call->getProject(), $call->getName()),
            $call->getPayload(),
            $call->headers + self::DEFAULT_HEADERS
        );

        if ($response->status != 200) {
            throw new \Exception('Calling hook ' . $call->getName() . ' failed. Error: ' . $response->body);
        }

        return $response->body;
    }

    private function makePath(string ...$uri): string
    {
        return self::BASE_PATH . implode("/", $uri);
    }
}
