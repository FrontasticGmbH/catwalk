<?php

namespace Frontastic\Catwalk\FrontendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SessionDeleteCommand extends ContainerAwareCommand
{
    const LIMIT_TO = 100;
    protected function configure()
    {
        $this
            ->setName('frontastic:cache:delete')
            ->setDescription('Clears all expired session data in batches');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('database_connection');

        $loopCheck = true;

        while ($loopCheck) {
            $sql = 'SELECT sess_id FROM http_session WHERE sess_time < :time LIMIT :LIMIT_TO';
            $maxLifetime = (int)ini_get('session.gc_maxlifetime');
            $select = $connection->prepare($sql);
            $select->bindValue(':LIMIT_TO', self::LIMIT_TO, \PDO::PARAM_INT);
            $select->bindValue(':time', time() - $maxLifetime, \PDO::PARAM_INT);
            $select->execute();
            $result = $select->fetchAll();
            if ($select->rowCount() < self::LIMIT_TO) {
                $loopCheck = false;
                continue;
            }

            $ids = [];
            foreach ($result as $item) {
                $ids[] = $item['sess_id'];
            }

            $chunk = array_chunk($ids, self::LIMIT_TO);
            foreach ($chunk as $item) {
                $sql = 'DELETE FROM http_session where sess_id in (?)';
                $select = $connection->executeUpdate($sql, [$item], [\Doctrine\DBAL\Connection::PARAM_STR_ARRAY]);
                sleep(0.1);
            }
            $output->writeln('Deleted sessions with ids:' . implode(',', $ids));
        }
    }
}
