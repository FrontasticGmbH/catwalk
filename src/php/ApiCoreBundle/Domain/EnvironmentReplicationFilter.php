<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

class EnvironmentReplicationFilter implements Target
{
    /**
     * @var Target
     */
    private $target;

    /**
     * @var Context
     */
    private $context;

    public function __construct(Target $replicationTarget, Context $context)
    {
        $this->target = $replicationTarget;
        $this->context = $context;
    }

    public function lastUpdate(): string
    {
        return $this->target->lastUpdate();
    }

    public function replicate(array $updates): void
    {
        $environment = $this->context->applicationEnvironment();

        foreach ($updates as $index => $update) {
            if (isset($update['environments']) &&
                (!isset($update['environments'][$environment]) || $update['environments'][$environment] === false)
            ) {
                $updates[$index]['isDeleted'] = true;
            }

            if (isset($update['environment']) && $update['environment'] !== $environment) {
                $updates[$index]['isDeleted'] = true;
            }
        }

        $this->target->replicate($updates);
    }
}
