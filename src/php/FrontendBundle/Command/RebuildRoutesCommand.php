<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class RebuildRoutesCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('frontastic:routes:rebuild')
            ->setDescription('Rebuild routes from node definitions');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->container->get(NodeService::class);
        /** @var RouteService $routeService */
        $routeService = $this->container->get(RouteService::class);

        $routeService->rebuildRoutes($nodeService->getNodes());

        return 0;
    }
}
