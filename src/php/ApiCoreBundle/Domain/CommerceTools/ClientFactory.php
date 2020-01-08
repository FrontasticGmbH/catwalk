<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\ClientFactory as ProjectClientFactory;
use Frontastic\Common\ReplicatorBundle\Domain\Project;

/**
 * Creates CommerceTools-Clients for the current project.
 */
class ClientFactory
{
    /**
     * @var Project
     */
    private $project;

    /**
     * @var ProjectClientFactory
     */
    private $projectClientFactory;

    public function __construct(Context $context, ProjectClientFactory $projectClientFactory)
    {
        $this->project = $context->project;
        $this->projectClientFactory = $projectClientFactory;
    }

    /**
     * Creates a CommerceTools Client for the given configuration section.
     * Example could be $factory->factorForConfigurationSection("product"), if
     * you want to use it in a product related call.  See project.yml for
     * possible configuration section names.
     */
    public function factorForConfigurationSection(string $configurationSectionName): Client
    {
        return $this->projectClientFactory->factorForProjectAndType(
            $this->project,
            $configurationSectionName
        );
    }
}
