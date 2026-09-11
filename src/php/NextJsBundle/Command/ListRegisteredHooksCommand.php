<?php

namespace Frontastic\Catwalk\NextJsBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'frontastic:nextjs:list-hooks', description: 'List the extensions registered for a project')]
class ListRegisteredHooksCommand extends Command
{
    private ExtensionService $hooksService;

    public function __construct(ExtensionService $hooksService)
    {
        $this->hooksService = $hooksService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('project', InputArgument::OPTIONAL, 'Project to list extensions for', 'demo_swiss');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(print_r(
            $this->hooksService->fetchProjectExtensions($input->getArgument('project')),
            true
        ));

        return 0;
    }
}
