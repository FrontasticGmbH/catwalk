<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frontastic:clear')
            ->setDescription('Clears all local data so that it will be rebuild by the replicator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('database_connection');
        $schemaManager = $connection->getSchemaManager();

        $query = '';
        $tables = $schemaManager->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if ($tableName === 'changelog') {
                continue;
            }

            $output->writeln('* Clearing table ' . $tableName);
            $connection->executeQuery('TRUNCATE ' . $tableName, [], []);
        }
    }
}
