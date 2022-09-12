<?php


namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Doctrine\ORM\EntityManagerInterface;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService;
use Frontastic\Catwalk\AppKernel;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\ServiceStatus;
use Frontastic\Common\HttpClient;
use RuntimeException;

class StatusService
{

    private array $services;
    private ExtensionService $extensionService;
    private EntityManagerInterface $entityManager;
    private HttpClient $httpClient;
    private ProjectService $projectService;

    public function __construct(
        ExtensionService $extensionService,
        EntityManagerInterface $entityManager,
        HttpClient $httpClient,
        ProjectService $projectService
    ) {
        $this->services = [
            "extensionrunner" => function () {
                return $this->handleExtensionRunnerStatus();
            },
            "database" => function () {
                return $this->handleDatabaseStatus();
            },
            "studio" => function () {
                return $this->handleStudioStatus();
            }
        ];

        $this->extensionService = $extensionService;
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
        $this->projectService = $projectService;
    }

    public function allServiceStatus(): array
    {
        $result = [];

        foreach ($this->services as $serviceName => $serviceFunction) {
            $result[$serviceName] = $serviceFunction();
        }

        return $result;
    }

    public function serviceStatus(string $serviceName): ServiceStatus
    {
        if (array_key_exists($serviceName, $this->services)) {
            return $this->services[$serviceName]();
        } else {
            throw new RuntimeException(sprintf("Cannot check status of %s, handler not found", $serviceName));
        }
    }

    private function handleExtensionRunnerStatus(): ServiceStatus
    {
        return $this->extensionService->status();
    }

    private function handleDatabaseStatus(): ServiceStatus
    {
        $start = microtime(true);

        $this->entityManager->getConnection()->connect();
        $connected = $this->entityManager->getConnection()->isConnected();

        $timeElapsedSecs = microtime(true) - $start;

        return new ServiceStatus([
            "status" => $connected,
            "up" => $connected,
            "responseTimeMs" => $timeElapsedSecs * 1000
        ]);
    }

    private function handleStudioStatus(): ServiceStatus
    {
        $projectInfo = $this->projectService->getProject();

        $customer = $projectInfo->customer;
        $environment = AppKernel::getEnvironmentFromConfiguration();

        $studioUrl = "$customer.frontastic" . ($environment == "dev" ? ".io.local" : ".io");

        $start = microtime(true);

        $response = $this->httpClient->get($studioUrl);

        $timeElapsedSecs = microtime(true) - $start;

        return new ServiceStatus([
            "status" => $response->status,
            "up" => $response->status < 300 || $response->status === 403,
            "responseTimeMs" => $timeElapsedSecs * 1000
        ]);
    }
}
