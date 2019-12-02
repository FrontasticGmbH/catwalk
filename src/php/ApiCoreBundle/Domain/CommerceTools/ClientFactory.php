<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools;

use Doctrine\Common\Cache\Cache;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\HttpClient;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;

/**
 * Creates CommerceTools-Clients for the current project.
 */
class ClientFactory
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var Cache
     */
    private $cache;

    public function __construct(
        Context $context,
        HttpClient $httpClient,
        Cache $cache
    ) {
        $this->customer = $context->customer;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    /**
     * Creates a CommerceTools Client for the given configuration section.
     * Example could be $factory->factorForConfigurationSection("product"), if
     * you want to use it in a product related call.  See project.yml for
     * possible configuration section names.
     */
    public function factorForConfigurationSection(string $configurationSectionName): Client
    {
        $config = $this->customer->configuration[$configurationSectionName];
        return new Client(
            $config->clientId,
            $config->clientSecret,
            $config->projectKey,
            $this->httpClient,
            $this->cache
        );
    }
}
