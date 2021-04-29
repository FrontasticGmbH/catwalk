<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Project;

class ProjectService
{
    /**
     * @var CustomerService
     */
    private $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function getProject(): Project
    {
        // In the context of a catwalk there is only 1 project available in the customer
        $project = reset($this->customerService->getCustomer()->projects);
        return ($project instanceof Project) ? $project : new Project();
    }
}
