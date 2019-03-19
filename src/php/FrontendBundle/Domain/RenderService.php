<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

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

    public function __construct(
        ContextService $contextService,
        ResponseDecorator $responseDecorator,
        HttpClient $httpClient,
        string $backendUrl
    ) {
        $this->contextService = $contextService;
        $this->responseDecorator = $responseDecorator;
        $this->httpClient = $httpClient;
        $this->backendUrl = $backendUrl;
    }

    public function render(Request $request, array $props = []): Response
    {
        $response = $this->httpClient->post(
            $this->backendUrl . $request->getPathInfo(),
            json_encode([
                'context' => $this->contextService->getContext(),
                'props' => $props,
            ]),
            [
                'Content-Type: application/json',
            ],
            new HttpClient\Options([
                'timeout' => \AppKernel::getDebug() ? 0.5 : 0.1,
            ])
        );

        if ($response->status >= 400) {
            $this->responseDecorator->setTimedOut();
        }

        return $response;
    }
}
