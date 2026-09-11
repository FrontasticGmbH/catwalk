<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'frontastic:clear', description: 'Clears all local data so that it will be rebuild by the replicator')]
class ClearCommand extends Command
{
    public function __construct(
        private readonly Connection $connection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $connection = $this->connection;
        $schemaManager = $connection->createSchemaManager();

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
