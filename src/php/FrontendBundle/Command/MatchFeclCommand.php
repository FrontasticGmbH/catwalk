<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use RulerZ\RulerZ;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MatchFeclCommand extends Command
{
    private RulerZ $rulerz;

    public function __construct(RulerZ $rulerz)
    {
        parent::__construct();
        $this->rulerz = $rulerz;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('frontastic:debug:match-fecl')
            ->setDescription('Match a FECL query against a payload')
            ->addArgument('query', InputArgument::REQUIRED)
            ->addArgument('payload', InputArgument::REQUIRED, 'Payload as JSON encoded')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $payload = json_decode($input->getArgument('payload'));
        if (!is_object($payload) && !is_array($payload)) {
            $output->writeln('<error>Invalid payload. Needs to be valid JSON object or array.');
            return 1;
        }

        $query = $input->getArgument('query');

        if ($this->rulerz->satisfies($payload, $query)) {
            $output->writeln('<info>Query matched payload</info>');
        } else {
            $output->writeln('<error>Query did not match payload</error>');
        }

        return 0;
    }
}
