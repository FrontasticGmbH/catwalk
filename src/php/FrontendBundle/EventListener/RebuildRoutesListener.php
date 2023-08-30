<?php
namespace Frontastic\Catwalk\FrontendBundle\EventListener;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RebuildRoutesListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(ContainerInterface $container, LoggerInterface $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
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

        $this->logger->debug('Routes rebuild in ' . __METHOD__ . '()');
    }
}
