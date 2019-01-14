<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RebuildRoutesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frontastic:routes:rebuild')
            ->setDescription('Rebuild routes from node definitions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var NodeService $nodeService */
        $nodeService = $this->getContainer()->get(NodeService::class);
        /** @var RouteService $routeService */
        $routeService = $this->getContainer()->get(RouteService::class);

        $routeService->rebuildRoutes($nodeService->getNodes());
    }
}
