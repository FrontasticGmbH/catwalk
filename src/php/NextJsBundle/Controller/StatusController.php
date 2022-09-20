<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\NextJsBundle\Domain\StatusService;
use Symfony\Component\HttpFoundation\JsonResponse;

class StatusController
{

    private StatusService $statusService;

    public function __construct(
        StatusService $statusService
    ) {
        $this->statusService = $statusService;
    }

    public function checkAllStatusAction(): JsonResponse
    {
        $status = $this->statusService->allServiceStatus();

        $response = new JsonResponse();
        $response->setStatusCode(200);
        $response->setContent(json_encode($status));

        return $response;
    }

    public function checkStatusAction(string $service): JsonResponse
    {
        $status = $this->statusService->serviceStatus($service);

        $response = new JsonResponse();

        $response->setStatusCode(200);
        $response->setContent(json_encode($status));
        return $response;
    }
}
