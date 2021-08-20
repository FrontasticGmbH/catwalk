<?php

namespace Frontastic\Catwalk\NextJsBundle\Command;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksApiClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListRegisteredHooksCommand extends Command
{
    private HooksApiClient $hooksApiClient;

    public function __construct(HooksApiClient $hooksApiClient)
    {
        $this->hooksApiClient = $hooksApiClient;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('frontastic:nextjs:list-hooks')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->hooksApiClient->getHooks('demo_swiss'));
        return 0;
    }
}
