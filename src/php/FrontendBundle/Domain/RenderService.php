<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Common\JsonSerializer;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\FrontendBundle\Domain\RenderService\ResponseDecorator;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\HttpClient;
use Frontastic\Common\HttpClient\Response;

class RenderService
{
    private $contextService;
    private $httpClient;
    private $backendUrl;

    /**
     * @var JsonSerializer
     */
    private $jsonSerializer;

    public function __construct(
        ContextService $contextService,
        ResponseDecorator $responseDecorator,
        HttpClient $httpClient,
        string $backendUrl,
        JsonSerializer $jsonSerializer
    ) {
        $this->contextService = $contextService;
        $this->responseDecorator = $responseDecorator;
        $this->httpClient = $httpClient;
        $this->backendUrl = $backendUrl;
        $this->jsonSerializer = $jsonSerializer;
    }

    public function render(Request $request, array $props = []): Response
    {
        $jsonString = json_encode(
            $this->jsonSerializer->serialize([
                'context' => $this->contextService->createContextFromRequest($request),
                'props' => $props,
                'queryParameters' => $request->query->all(),
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
        }

        $response->body = json_decode($response->body) ?: [
            'app' => $response->body,
            'helmet' => [
                'meta' => '',
                'title' => '',
            ],
        ];

        return $response;
    }
}
