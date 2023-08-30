<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Domnikl\Statsd\Client as StatsD;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class PageImpressionRecorder
{
    private $statsd;

    public function __construct(StatsD $statsd)
    {
        $this->statsd = $statsd;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $controller = $event->getRequest()->get('_controller');
        if (!in_array(
            $controller,
            [
                'Frontastic\\Catwalk\\FrontendBundle\\Controller\\NodeController::viewAction',
                'Frontastic\\Catwalk\\FrontendBundle\\Controller\\CategoryController::viewAction',
                'Frontastic\\Catwalk\\FrontendBundle\\Controller\\ProductController::viewAction',
            ]
        )) {
            return;
        }

        $viewType = 'default';
        if (preg_match('((?P<type>[A-Za-z]+)Controller::)', $controller, $match)) {
            $viewType = strtolower($match['type']);
        }
        $this->statsd->increment('catwalk.pi.' . $viewType . '.count');
    }
}
