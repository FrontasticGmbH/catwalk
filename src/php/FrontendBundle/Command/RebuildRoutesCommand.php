<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

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
        $nodeService = $this->getContainer()->get('Frontastic\Catwalk\FrontendBundle\Domain\NodeService');
        $routeService = $this->getContainer()->get('Frontastic\Catwalk\FrontendBundle\Domain\RouteService');

        $routeService->rebuildRoutes($nodeService->getNodes());
    }
}
