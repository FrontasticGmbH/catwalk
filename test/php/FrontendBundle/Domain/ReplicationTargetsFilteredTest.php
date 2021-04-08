<?php


namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\AppService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\EnvironmentReplicationFilter;
use Frontastic\Catwalk\IntegrationTest;
use Frontastic\Common\ReplicatorBundle\Domain\EndpointService;

class ReplicationTargetsFilteredTest extends IntegrationTest
{
    private const REPLICATION_FILTER_EXCLUDE_LIST = [
        // Apps only have 1 definition (data integrity, migration, etc.)
        AppService::class,
    ];

    public function testReplicationTargetsFilteredByEnvironment()
    {
        /** @var EndpointService $endpointService */
        $endpointService = $this->getContainer()->get(EndpointService::class);

        $allTargets = $this->getRegisteredReplicationTargets($endpointService);

        foreach ($allTargets as $target) {
            if (in_array(get_class($target), self::REPLICATION_FILTER_EXCLUDE_LIST)) {
                continue;
            }

            $this->assertInstanceOf(EnvironmentReplicationFilter::class, $target);
        }
    }

    /**
     * Thank you, PHPUnit, for deprecating the useful method to access a private property.
     */
    private function getRegisteredReplicationTargets(EndpointService $endpointService): array
    {
        $reflectionObject = new \ReflectionObject($endpointService);
        $property = $reflectionObject->getProperty('targets');
        $property->setAccessible(true);
        return $property->getValue($endpointService);
    }
}
