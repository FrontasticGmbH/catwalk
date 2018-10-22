<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\ApiBundle\Domain\Context;
use Frontastic\Common\ReplicatorBundle\Domain\Result;
use Frontastic\Common\ReplicatorBundle\Domain\Command;

class ApiController extends Controller
{
    public function contextAction(Request $request)
    {
        $contextService = $this->get('Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService');
        $jsonSerializer = $this->get('Frontastic\Common\JsonSerializer');

        return $contextService->createContextFromRequest($request);
    }

    public function endpointAction(Request $request): JsonResponse
    {
        try {
            $requestVerifier = $this->get('Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier');
            $requestVerifier->ensure($request, $this->getParameter('secret'));

            if (!$request->getContent() ||
                !($body = json_decode($request->getContent(), true))) {
                throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
            }

            $command = new Command($body);
            $endpoint = $this->get('Frontastic\Common\ReplicatorBundle\Domain\EndpointService');
            return new JsonResponse($endpoint->dispatch($command));
        } catch (\Throwable $e) {
            return new JsonResponse(Result::fromThrowable($e));
        }
    }
}
