<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

class EnvironmentReplicationFilterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Target
     */
    private $innerTargetMock;

    public function setUp()
    {
        $this->innerTargetMock = \Phake::mock(Target::class);
    }

    public function testReplicateWithoutChanges()
    {
        $filter = new EnvironmentReplicationFilter($this->innerTargetMock, 'development');

        $updates = [
            [
                'someId' => 'a',
                'environments' => $this->selectedEnvironments(['development']),
                'isDeleted' => false,
            ],
            [
                'someId' => 'b',
                'environments' => $this->selectedEnvironments(['development']),
                'isDeleted' => true,
            ]
        ];

        $filter->replicate($updates);

        \Phake::verify($this->innerTargetMock)->replicate($updates);
    }

    public function testReplicateWithDeletingNonEnvironmentEntities()
    {
        $filter = new EnvironmentReplicationFilter($this->innerTargetMock, 'production');

        $updates = [
            [
                'someId' => 'a',
                'environments' => $this->selectedEnvironments(['development', 'staging', 'production']),
                'isDeleted' => false,
            ],
            [
                'someId' => 'b',
                'environments' => $this->selectedEnvironments(['development', 'staging']),
                'isDeleted' => false,
            ]
        ];

        $filter->replicate($updates);

        $expectedUpdates = $updates;
        $expectedUpdates[1]['isDeleted'] = true;

        \Phake::verify($this->innerTargetMock)->replicate($expectedUpdates);
    }

    private function selectedEnvironments(array $environments = []): array
    {
        $environmentSelection = [
            'development' => false,
            'staging' => false,
            'production' => false,
        ];

        foreach ($environments as $environment) {
            $environmentSelection[$environment] = true;
        }
        return $environmentSelection;
    }
}
