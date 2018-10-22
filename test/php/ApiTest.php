<?php

namespace Frontastic\Catwalk;

use Frontastic\Common\HttpClient;
use Frontastic\Catwalk\IntegrationTest;

abstract class ApiTest extends IntegrationTest
{
    protected $httpClient;

    public function request(
        string $method,
        string $routeId,
        array $routeParameters = array(),
        $body = null,
        array $headers = []
    ) {
        $server = getenv('SERVER') ?: 'http://backend.frontastic.io.local';
        $client = $this->getHttpClient();
        $client->addDefaultHeaders([
            'X-Requested-With: XMLHttpRequest',
        ]);

        $route = $this->getContainer()->get('router')->generate($routeId, $routeParameters);
        $this->assertNotNull($route, "Could not find route $routeId");

        $body = $body ? json_encode($body) : '';
        $options = new HttpClient\Options([
            // Must work for initial Symfony Cache priming
            'timeout' => 10,
        ]);
        $response = $client->request($method, $server . $route, $body, $headers, $options);

        if (isset($response->headers['set-cookie'])) {
            $cookie = explode(';', $response->headers['set-cookie'])[0];
            $client->addDefaultHeaders([
                'Cookie: ' . $cookie,
            ]);
        }

        $decoded = json_decode($response->body);
        $this->assertNotNull($decoded, 'Failed to decode response body: ' . strip_tags($response->body));
        $response->body = $decoded;
        return $response;
    }

    private function getHttpClient(): HttpClient
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient\Stream();
        }

        return $this->httpClient;
    }
}
