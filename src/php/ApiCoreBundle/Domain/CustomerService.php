<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\Functions;
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

        if (!file_exists($this->projectFile)) {
            return $this->customer = new Customer();
        }

        $project = Yaml::parse(file_get_contents($this->projectFile));

        if (in_array($this->environment, ['prod', 'production']) &&
            file_exists($this->projectFile . '.decrypted')
        ) {
            $project = Functions::array_merge_recursive(
                $project,
                Yaml::parse(file_get_contents($this->projectFile . '.decrypted'))
            );
        }

        if (!in_array($this->environment, ['prod', 'production']) &&
            file_exists($this->projectFile . '.' . $this->environment)) {
            $project = Functions::array_merge_recursive(
                $project,
                Yaml::parse(file_get_contents($this->projectFile . '.' . $this->environment))
            );
        }

        if (file_exists($this->projectFile . '.' . $this->environment . '.decrypted')) {
            $project = Functions::array_merge_recursive(
                $project,
                Yaml::parse(file_get_contents($this->projectFile . '.' . $this->environment . '.decrypted'))
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
