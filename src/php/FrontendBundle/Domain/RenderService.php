<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator;
use Frontastic\Common\HttpClient;
use Frontastic\Common\HttpClient\Response;
use Frontastic\Common\JsonSerializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class RenderService
{
    /**
     * @var ContextService
     */
    private $contextService;

    /**
     * @var ResponseDecorator
     */
    private $responseDecorator;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $backendUrl;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ContextService $contextService,
        ResponseDecorator $responseDecorator,
        HttpClient $httpClient,
        string $backendUrl,
        JsonSerializer $jsonSerializer,
        LoggerInterface $logger
    ) {
        $this->contextService = $contextService;
        $this->responseDecorator = $responseDecorator;
        $this->httpClient = $httpClient;
        $this->backendUrl = $backendUrl;
        $this->jsonSerializer = $jsonSerializer;
        $this->logger = $logger;
    }

    public function render(Request $request, array $props = []): Response
    {
        $jsonString = json_encode(
            $this->jsonSerializer->serialize([
                'context' => $this->contextService->createContextFromRequest($request),
                'props' => $props,
                'queryParameters' => $request->query->all(),
                'requestUri' => $request->getUri(),
            ])
        );

        $response = $this->httpClient->post(
            $this->backendUrl . $request->getPathInfo(),
            $jsonString,
            [
                'Content-Type: application/json',
            ],
            new HttpClient\Options([
                'timeout' => \AppKernel::getDebug() ? 5.0 : 0.5,
            ])
        );

        if ($response->status >= 400) {
            $this->responseDecorator->setTimedOut();

            $this->logger->error(
                sprintf('Server side rendering (SSR) failed with status code %d', $response->status)
            );
        }

        $ssrResponse = json_decode($response->body, true);
        $response->body = $ssrResponse ?: [
            'app' => $response->body,
            'helmet' => [
                'meta' => '',
                'title' => '',
                'script' => '',
            ],
        ];

        if ($ssrResponse) {
            // make sure properties are set properly as there might be errors otherwise
            $response->body['helmet']['meta'] = $response->body['helmet']['meta'] ?? '';
            $response->body['helmet']['title'] = $response->body['helmet']['title'] ?? '';
            $response->body['helmet']['script'] = $response->body['helmet']['script'] ?? '';
        }

        return $response;
    }
}
