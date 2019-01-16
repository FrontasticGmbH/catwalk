<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\Yaml\Yaml;

class CustomerService
{
    private $projectFile;

    public function __construct(string $projectFile)
    {
        $this->projectFile = $projectFile;
    }

    public function getCustomer(): Customer
    {
        $project = Yaml::parse(file_get_contents($this->projectFile));
        return new Customer([
            'name' => $project['customer'],
            'secret' => $project['apiKey'],
            'configuration' => array_map(
                function (array $values) {
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
