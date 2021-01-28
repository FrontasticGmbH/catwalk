<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller;

use Frontastic\ApiBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ReplicatorBundle\Domain\Command;
use Frontastic\Common\ReplicatorBundle\Domain\EndpointService;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Frontastic\Common\ReplicatorBundle\Domain\Result;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends Controller
{
    const VERSION_PARAMETER_NAME = 'version';
    const DEFAULT_VERSION = 'unknown';

    public function contextAction(Request $request)
    {
        $contextService = $this->get(ContextService::class);
        return $contextService->createContextFromRequest($request);
    }

    public function endpointAction(Request $request): JsonResponse
    {
        if (class_exists('Tideways\Profiler')) {
            \Tideways\Profiler::setServiceName('ReplicatorEndpoint');
        }

        try {
            $this->verifyRequest($request);

            /* API requests don't need a session. */
            $request->attributes->set(Session::STATELESS, true);

            if (!$request->getContent() ||
                !($body = json_decode($request->getContent(), true))) {
                throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
            }

            $command = new Command($body, true);
            $endpoint = $this->get(EndpointService::class);
            return new JsonResponse($endpoint->dispatch($command));
        } catch (\Throwable $e) {
            return new JsonResponse(Result::fromThrowable($e));
        }
    }

    public function versionAction(Request $request): JsonResponse
    {
        try {
            $this->verifyRequest($request);

            if (($versionFromEnvironment = getenv('version')) !== false) {
                $version = $versionFromEnvironment;
            } elseif ($this->container->hasParameter(self::VERSION_PARAMETER_NAME)) {
                $version = $this->container->getParameter(self::VERSION_PARAMETER_NAME);
            } else {
                $version = self::DEFAULT_VERSION;
            }

            return new JsonResponse([
                'ok' => true,
                'version' => $version,
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse(Result::fromThrowable($exception));
        }
    }

    private function verifyRequest(Request $request): void
    {
        $requestVerifier = $this->get(RequestVerifier::class);
        $requestVerifier->ensure($request, $this->getParameter('secret'));

        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);
    }
}
