<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Project;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;

class ProjectController
{
    private FromFrontasticReactMapper $mapper;

    public function __construct(FromFrontasticReactMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function indexAction(Context $context)
    {
        /**
         * @var Project $project
         */
        $project = $this->mapper->map($context->project);

        // Exclude sensitive information (credentials)
        return new Project([
            'projectId' => $project->projectId,
            'name' => $project->name,
            'customer' => $project->customer,
            'locales' => $project->locales,
            'defaultLocale' => $project->defaultLocale,
            'configuration' => []
        ]);
    }
}
