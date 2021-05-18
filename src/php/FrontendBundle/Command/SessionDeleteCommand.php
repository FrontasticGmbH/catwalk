<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SessionDeleteCommand extends ContainerAwareCommand
{
    const LIMIT_TO = 1000;

    protected function configure()
    {
        $this
            ->setName('frontastic:session:delete')
            ->setDescription('Clears all expired session data in batches')
            ->addArgument(
                'batchLimit',
                InputArgument::OPTIONAL,
                'Delete Batch limit'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $batchLimit = (int) $input->getArgument('batchLimit') ?: self::LIMIT_TO;
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
                $loopCheck = false;
                continue;
            }

            $ids = [];
            foreach ($result as $item) {
                $ids[] = $item['sess_id'];
            }

            $chunk = array_chunk($ids, $batchLimit);
            foreach ($chunk as $item) {
                $sql = 'DELETE FROM http_session where sess_id in (?) AND sess_time < ? LIMIT ?';
                $connection->executeUpdate(
                    $sql,
                    [$item, time() - $maxLifetime, $batchLimit],
                    [\Doctrine\DBAL\Connection::PARAM_STR_ARRAY, \PDO::PARAM_INT, \PDO::PARAM_INT]
                );
                sleep(0.1);
            }

            $output->writeln('Deleted sessions with ids:' . implode(',', $ids));

            if ($select->rowCount() < $batchLimit) {
                $loopCheck = false;
                continue;
            }
        }
    }
}
