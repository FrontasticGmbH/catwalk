<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\HttpClient;
use Frontastic\Common\HttpClient\Response;

class WirecardClient
{
    /** @var HttpClient */
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function post(string $path, array $body, WirecardCredentials $credentials): array
    {
        return $this->httpClient
            ->postAsync(
                'https://' . $credentials->host . $path,
                json_encode($body),
                [
                    'Authorization: Basic ' . base64_encode($credentials->user . ':' . $credentials->password),
                    'Content-Type: application/json',
                ],
                new HttpClient\Options([
                    'timeout' => 10,
                ])
            )
            ->then(function (Response $response): array {
                $body = json_decode($response->body, true);
                if (!is_array($body)) {
                    throw new WirecardResponseException('Invalid JSON body in response', $response);
                }

                if (isset($body['errors'])) {
                    throw new WirecardResponseException(
                        'Errors in body: ' . $this->errorDataToString($body['errors']),
                        $response
                    );
                }

                if ($response->status >= 400) {
                    throw new WirecardResponseException('HTTP status code ' . $response->status, $response);
                }

                return $body;
            })
            ->wait();
    }

    private function errorDataToString($errorData): string
    {
        if (!is_array($errorData)) {
            return 'unknown errors';
        }

        return implode(
            ', ',
            array_map(
                function ($error): string {
                    if (!is_array($error)) {
                        return 'unknown error';
                    }

                    return ($error['code'] ?? '<unknown>') . ': ' .
                        ($error['description'] ?? 'Unknown Error');
                },
                $errorData
            )
        );
    }
}
