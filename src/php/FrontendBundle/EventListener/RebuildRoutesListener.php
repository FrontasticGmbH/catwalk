<?php
namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RebuildRoutesListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (false === $event->isMasterRequest()) {
            return;
        }

        /** @var RouteService $routeService */
        $routeService = $this->container->get(RouteService::class);
        if (count($routes = $routeService->getRoutes()) > 0) {
            return;
        }

        /** @var NodeService $nodeService */
        $nodeService = $this->container->get(NodeService::class);
        $routeService->rebuildRoutes($nodeService->getNodes());

        /** @var LoggerInterface $logger */
        $logger = $this->container->get(LoggerInterface::class);
        $logger->info('Routes rebuild in ' . __METHOD__ . '()');
    }
}
