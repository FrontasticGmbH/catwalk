<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;


use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
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
        return $this->mapper->map($context->project);
    }
}
