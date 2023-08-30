<?php

namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Common\ReplicatorBundle\Domain\Project;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ReferrerPolicy
{
    const DEFAULT = 'same-origin';

    /**
     * @var Project
     */
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $event->getResponse()->headers->set(
            'Referrer-Policy',
            $this->project->configuration['referrerPolicy'] ?? self::DEFAULT
        );
    }
}
