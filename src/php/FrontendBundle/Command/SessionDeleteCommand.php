<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'frontastic:session:delete',
    description: 'Clears all expired session data in batches, parameter batch limit, careful exceeding memory limits'
)]
class SessionDeleteCommand extends Command
{
    const DEFAULT_ROWS_TO_DELETE_PER_EXECUTION = 1000;

    public function __construct(
        private readonly Connection $connection
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'batchLimit',
                InputArgument::OPTIONAL,
                'Delete Batch limit',
                self::DEFAULT_ROWS_TO_DELETE_PER_EXECUTION
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $batchLimit = (int) $input->getArgument('batchLimit');
        $connection = $this->connection;
        $loopCheck = true;

        while ($loopCheck) {
            $sql = 'SELECT sess_id FROM http_session WHERE sess_time < :time LIMIT :LIMIT_TO';
            $maxLifetime = (int)ini_get('session.gc_maxlifetime');
            $ids = $connection->executeQuery(
                $sql,
                [
                    'LIMIT_TO' => $batchLimit,
                    'time' => time() - $maxLifetime,
                ],
                [
                    'LIMIT_TO' => ParameterType::INTEGER,
                    'time' => ParameterType::INTEGER,
                ]
            )->fetchFirstColumn();
            $rowCount = count($ids);
            if (!$rowCount) {
                $output->writeln('No sessions to clear');
                break;
            }

            $sql = 'DELETE FROM http_session where sess_id in (?) AND sess_time < ? LIMIT ?';
            $connection->executeStatement(
                $sql,
                [$ids, time() - $maxLifetime, $batchLimit],
                [ArrayParameterType::STRING, ParameterType::INTEGER, ParameterType::INTEGER]
            );

            usleep(50000);

            if ($rowCount < $batchLimit) {
                $loopCheck = false;
                $output->writeln('Sessions deleted');
                continue;
            }
        }

        return 0;
    }
}
