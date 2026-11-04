<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService;
use Frontastic\Catwalk\IntegrationTestCase;
use Frontastic\Common\HttpClient;

/**
 * @group integration
 */
class StatusServiceIntegrationTest extends IntegrationTestCase
{
    public function testDatabaseStatusIsUpWithRealConnection()
    {
        $statusService = new StatusService(
            $this->createMock(ExtensionService::class),
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->createMock(HttpClient::class),
            $this->createMock(ProjectService::class)
        );

        $status = $statusService->serviceStatus('database');

        $this->assertTrue($status->up);
        $this->assertGreaterThan(0, $status->responseTimeMs);
    }
}
