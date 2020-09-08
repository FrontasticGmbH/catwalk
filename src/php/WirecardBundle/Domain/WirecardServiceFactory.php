<?php

namespace Frontastic\Catwalk\WirecardBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Project;

class WirecardServiceFactory
{
    /** @var WirecardClient */
    private $wirecardClient;

    public function __construct(WirecardClient $wirecardClient)
    {
        $this->wirecardClient = $wirecardClient;
    }

    public function factor(Project $project): ?WirecardService
    {
        $wirecardConfig = $project->getConfigurationSection('payment', 'wirecard');

        $credentials = [];
        foreach ((array)($wirecardConfig->credentials ?? []) as $method => $credentialsConfig) {
            $credentials[$method] = new WirecardCredentials($credentialsConfig);
        }

        return new WirecardService($this->wirecardClient, $credentials);
    }
}
