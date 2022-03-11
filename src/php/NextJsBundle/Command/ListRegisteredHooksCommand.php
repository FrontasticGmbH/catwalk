<?php

namespace Frontastic\Catwalk\NextJsBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRegisteredHooksCommand extends Command
{
    private HooksService $hooksService;

    public function __construct(HooksService $hooksService)
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
        var_dump($this->hooksService->fetchProjectHooks('demo_swiss'));
        return 0;
    }
}
