<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ReplicatorBundle\Domain\Command;
use Frontastic\Common\ReplicatorBundle\Domain\EndpointService;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Frontastic\Common\ReplicatorBundle\Domain\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiController extends AbstractController
{
    const VERSION_PARAMETER_NAME = 'version';
    const DEFAULT_VERSION = 'unknown';
    const SUPPORTED_FEATURES = [
        'queryProductsByMultipleCategories',
        'dataSourcePreview'
    ];

    private ContextService $contextService;
    private EndpointService $endpointService;
    private RequestVerifier $requestVerifier;
    private string $secret;

    public function __construct(
        ContextService $contextService,
        EndpointService $endpointService,
        RequestVerifier $requestVerifier,
        string $secret
    ) {
        $this->contextService = $contextService;
        $this->endpointService = $endpointService;
        $this->requestVerifier = $requestVerifier;
        $this->secret = $secret;
    }

    public function contextAction(Request $request)
    {
        return $this->contextService->createContextFromRequest($request);
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

            $this->verifyProject($request);

            $command = new Command($body, true);
            return new JsonResponse($this->endpointService->dispatch($command));
        } catch (\Throwable $e) {
            return new JsonResponse(Result::fromThrowable($e));
        }
    }

    /**
     * This endpoint provides information about the current library versions
     * and supported features to the replicator and studio.
     */
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
                'catwalkVersion' => $this->getCatwalkVersion(),
                'commonVersion' => $this->getCommonVersion(),
                'supportedFeatures' => self::SUPPORTED_FEATURES
            ]);
        } catch (\Throwable $exception) {
            return new JsonResponse(Result::fromThrowable($exception));
        }
    }

    private function getCatwalkVersion()
    {
        if (!class_exists(\Composer\InstalledVersions::class)) {
            return null;
        }
        try {
            return \Composer\InstalledVersions::getPrettyVersion('frontastic/catwalk');
        } catch (\OutOfBoundsException $e) {
            return null;
        }
    }

    private function getCommonVersion()
    {
        if (!class_exists(\Composer\InstalledVersions::class)) {
            return null;
        }
        try {
            return \Composer\InstalledVersions::getPrettyVersion('frontastic/common');
        } catch (\OutOfBoundsException $e) {
            return null;
        }
    }

    public function clearAction(Request $request): JsonResponse
    {
        try {
            $this->verifyRequest($request);
        } catch (\Throwable $exception) {
            return new JsonResponse(Result::fromThrowable($exception), $exception->getCode() ?: 500);
        }

        $connection = $this->get('database_connection');
        $schemaManager = $connection->getSchemaManager();

        $tables = $schemaManager->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if ($tableName === 'changelog') {
                continue;
            }

            $connection->executeQuery('TRUNCATE `' . $tableName . '`', [], []);
        }

        return new JsonResponse([
            'ok' => true,
        ]);
    }

    private function verifyRequest(Request $request): void
    {
        $this->requestVerifier->ensure($request, $this->secret);

        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);
    }

    private function verifyProject(Request $request): void
    {
        $json = json_decode($request->getContent(), true);
        if (!empty($json['payload']['projectId'])) {
            $payloadProject = $json['payload']['projectId'];
            $endpointProject = $this->contextService->createContextFromRequest($request)->project->projectId;

            if ($payloadProject != $endpointProject) {
                throw new \InvalidArgumentException(
                    sprintf(
                        "ProjectId in payload (%s) and projectId of this endpoint (%s) don't match.",
                        $payloadProject,
                        $endpointProject
                    )
                );
            }
        }
    }
}
