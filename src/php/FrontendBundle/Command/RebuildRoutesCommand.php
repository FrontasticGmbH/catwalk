<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\RouteService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'frontastic:routes:rebuild', description: 'Rebuild routes from node definitions')]
class RebuildRoutesCommand extends Command
{
    public function __construct(
        private readonly NodeService $nodeService,
        private readonly RouteService $routeService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->routeService->rebuildRoutes($this->nodeService->getNodes());

        return 0;
    }
}
