<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Symfony\Component\Yaml\Yaml;

class RemoteDecoratorFactory
{
    private $configurationDirectory;

    private $apiCalls = null;

    private $apis = [
        'product',
        'search',
        'content',
        'cart',
        'wishlist',
        'account',
    ];

    public function __construct(string $configurationDirectory)
    {
        $this->configurationDirectory = $configurationDirectory;
    }

    private function parseConfigurations()
    {
        if ($this->apiCalls !== null) {
            return;
        }

        $this->apiCalls = [];

        foreach (glob($this->configurationDirectory . '/*.yml') as $configurationFile) {
            $configuration = Yaml::parseFile($configurationFile);

            if (!isset($configuration['api']) || !in_array($configuration['api'], $this->apis)) {
                throw new \OutOfBoundsException('API (`api:`) must be set to one of: ' . implode(', ', $this->apis));
            }

            foreach (($configuration['decorators'] ?? []) as $decorator) {
                if (!isset($decorator['method'])) {
                    throw new \OutOfBoundsException('Method (`method:`) must be set for decorator');
                }

                // @TODO: Validate method against include list?

                if (isset($decorator['before'])) {
                    $this->apiCalls[$configuration['api']]['before' . ucfirst($decorator['method'])][] =
                        new RemoteDecorator\Endpoint($decorator['before']);
                }

                if (isset($decorator['after'])) {
                    $this->apiCalls[$configuration['api']]['after' . ucfirst($decorator['method'])][] =
                        new RemoteDecorator\Endpoint($decorator['after']);
                }
            }
        }
    }

    /**
     * @return RemoteDecorator\Endpoint[]
     */
    public function getProductApiCalls(): array
    {
        $this->parseConfigurations();

        return $this->apiCalls['product'] ?? [];
    }
}
