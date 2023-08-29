<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ClearCommand extends Command
{
    use ContainerAwareTrait;

    protected function configure()
    {
        $this
            ->setName('frontastic:clear')
            ->setDescription('Clears all local data so that it will be rebuild by the replicator');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->container->get('database_connection');
        $schemaManager = $connection->getSchemaManager();

        $query = '';
        $tables = $schemaManager->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if ($tableName === 'changelog') {
                continue;
            }

            $output->writeln('* Clearing table ' . $tableName);
            $connection->executeQuery('TRUNCATE `' . $tableName . '`', [], []);
        }

        return 0;
    }
}
