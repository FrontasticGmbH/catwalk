<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextDecorator;
use Frontastic\Catwalk\FrontendBundle\Gateway\ProjectConfigurationGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

class ProjectConfigurationService implements Target, ContextDecorator
{
    /**
     * @var ProjectConfigurationGateway
     */
    private $projectConfigurationGateway;

    public function __construct(ProjectConfigurationGateway $projectConfigurationGateway)
    {
        $this->projectConfigurationGateway = $projectConfigurationGateway;
    }

    public function lastUpdate(): string
    {
        return $this->projectConfigurationGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $projectConfiguration =
                    $this->projectConfigurationGateway->getEvenIfDeleted($update['projectConfigurationId']);
            } catch (\OutOfBoundsException $exception) {
                $projectConfiguration = new ProjectConfiguration([
                    'projectConfigurationId' => $update['projectConfigurationId'],
                ]);
            }

            $projectConfiguration = $this->fill($projectConfiguration, $update);
            $this->projectConfigurationGateway->store($projectConfiguration);
        }
    }

    public function decorate(Context $context): Context
    {
        $projectConfiguration = $this->projectConfigurationGateway->getCurrentConfiguration();
        if ($projectConfiguration !== null && is_array($projectConfiguration->configuration)) {
            $context->projectConfiguration = $projectConfiguration->configuration;
        }
        return $context;
    }

    private function fill(ProjectConfiguration $projectConfiguration, array $data): ProjectConfiguration
    {
        $projectConfiguration->configuration = $data['configuration'];
        $projectConfiguration->sequence = $data['sequence'];
        $projectConfiguration->metaData = $data['metaData'];
        $projectConfiguration->isDeleted = (bool)$data['isDeleted'];

        return $projectConfiguration;
    }
}
