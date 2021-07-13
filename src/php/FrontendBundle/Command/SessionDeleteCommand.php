<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SessionDeleteCommand extends ContainerAwareCommand
{
    const DEFAULT_ROWS_TO_DELETE_PER_EXECUTION = 1000;

    protected function configure()
    {
        $this
            ->setName('frontastic:session:delete')
            ->setDescription(
                'Clears all expired session data in batches, parameter batch limit, careful exceeding memory limits'
            )
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
        $connection = $this->getContainer()->get('database_connection');
        $loopCheck = true;

        while ($loopCheck) {
            $sql = 'SELECT sess_id FROM http_session WHERE sess_time < :time LIMIT :LIMIT_TO';
            $maxLifetime = (int)ini_get('session.gc_maxlifetime');
            $select = $connection->prepare($sql);
            $select->bindValue(':LIMIT_TO', $batchLimit, \PDO::PARAM_INT);
            $select->bindValue(':time', time() - $maxLifetime, \PDO::PARAM_INT);
            $select->execute();
            $result = $select->fetchAll();
            if (!$select->rowCount()) {
                $output->writeln('No sessions to clear');
                break;
            }

            $ids = [];
            foreach ($result as $item) {
                $ids[] = $item['sess_id'];
            }

            $sql = 'DELETE FROM http_session where sess_id in (?) AND sess_time < ? LIMIT ?';
            $connection->executeUpdate(
                $sql,
                [$ids, time() - $maxLifetime, $batchLimit],
                [\Doctrine\DBAL\Connection::PARAM_STR_ARRAY, \PDO::PARAM_INT, \PDO::PARAM_INT]
            );

            usleep(50000);

            if ($select->rowCount() < $batchLimit) {
                $loopCheck = false;
                $output->writeln('Sessions deleted');
                continue;
            }
        }

        return 0;
    }
}
