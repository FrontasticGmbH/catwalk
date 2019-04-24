<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\Yaml\Yaml;

class CustomerService
{
    private $projectFile;

    private $environment;

    private $customer = null;

    public function __construct(string $projectFile)
    {
        $this->projectFile = $projectFile;
        $this->environment = \Frontastic\Catwalk\AppKernel::getEnvironmentFromConfiguration();
    }

    public function getCustomer(): Customer
    {
        if ($this->customer) {
            return $this->customer;
        }

        $project = Yaml::parse(file_get_contents($this->projectFile));

        if (!in_array($this->environment, ['prod', 'production']) &&
            file_exists($this->projectFile . '.' . $this->environment)) {
            $project = array_merge_recursive(
                $project,
                Yaml::parse(file_get_contents($this->projectFile . '.' . $this->environment))
            );
        }

        return $this->customer = new Customer([
            'name' => $project['customer'],
            'secret' => $project['apiKey'],
            'configuration' => array_map(
                function ($values) {
                    return is_array($values) ? (object) $values : $values;
                },
                $project['configuration']
            ),
            'projects' => [
                new Project($project),
            ],
        ]);
    }
}
