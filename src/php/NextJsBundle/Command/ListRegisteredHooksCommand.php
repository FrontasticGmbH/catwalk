<?php

namespace Frontastic\Catwalk\NextJsBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRegisteredHooksCommand extends Command
{
    private ExtensionService $hooksService;

    public function __construct(ExtensionService $hooksService)
    {
        $this->hooksService = $hooksService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('frontastic:nextjs:list-hooks')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->hooksService->fetchProjectExtensions('demo_swiss'));
        return 0;
    }
}
