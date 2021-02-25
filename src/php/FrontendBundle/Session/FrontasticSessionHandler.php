<?php

namespace Frontastic\Catwalk\FrontendBundle\Session;

use Doctrine\DBAL\Connection;
use PDO;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\AbstractSessionHandler;

/**
 * Tailored version of Symfony PdoSessionHandler
 *
 * First and foremost the goal is to only generate writes to the database when the session data has changed.
 * Additionally we update the session once every $writeThresholdInSeconds to mark the user as active.
 *
 * With the stock Symfony handler the UPDATE call happens every request, leading to more database load than the rest
 * of our application generates and thus creating an avoidable need to scale the Database.
 */
class FrontasticSessionHandler extends AbstractSessionHandler
{
    /** @var Connection */
    private $databaseConnection;

    /** @var array */
    private $sessions;

    /** @var bool */
    private $shouldCleanUpExpiredSessions = false;

    /** @var int How many seconds will we not refresh the session lifetime */
    private $writeThresholdInSeconds;

    public function __construct(Connection $connection, $writeThresholdInSeconds)
    {
        $this->databaseConnection = $connection;
        $this->writeThresholdInSeconds = $writeThresholdInSeconds;
    }

    protected function connection(): Connection
    {
        return $this->databaseConnection;
    }

    /** @return string */
    public function doRead($sessionId)
    {
        $query = <<<SQL
SELECT sess_data, sess_time FROM http_session WHERE sess_id = :id
SQL;
        $select = $this->connection()->prepare($query);
        $select->bindParam(':id', $sessionId, PDO::PARAM_STR);
        $select->execute();

        $row = $select->fetch(PDO::FETCH_NUM);
        if (!$row) {
            return '';
        }
        $this->sessions[$sessionId] = ['data' => $row[0], 'time' => $row[1]];
        return $row[0];
    }

    protected function doWrite($sessionId, $data)
    {
        if (isset($this->sessions[$sessionId])
            && $data === $this->sessions[$sessionId]['data']
            && time() > $this->sessions[$sessionId]['time'] - $this->writeThresholdInSeconds
        ) {
            return true;
        }

        $query = <<<SQL
INSERT INTO http_session
    (sess_id, sess_data, sess_lifetime, sess_time)
    VALUES (:id, :data, :expiry, :time)
    ON DUPLICATE KEY UPDATE
        sess_data = VALUES(sess_data), sess_lifetime = VALUES(sess_lifetime), sess_time = VALUES(sess_time)
SQL;
        $insert = $this->connection()->prepare($query);
        $insert->bindParam(':id', $sessionId, PDO::PARAM_STR);
        $insert->bindParam(':data', $data, PDO::PARAM_LOB);
        $insert->bindValue(':time', time(), PDO::PARAM_INT);

        $maxLifetime = (int) ini_get('session.gc_maxlifetime');
        $insert->bindValue(':expiry', time() + $maxLifetime, PDO::PARAM_INT);

        $insert->execute();

        return true;
    }

    protected function doDestroy($sessionId)
    {
        $query = <<<SQL
DELETE FROM http_session WHERE sess_id = :id
SQL;
        $delete = $this->connection()->prepare($query);
        $delete->bindParam(':id', $sessionId, PDO::PARAM_STR);
        $delete->execute();

        return true;
    }

    /** @noinspection PhpParameterNameChangedDuringInheritanceInspection */
    public function gc($maxLifetime)
    {
        $this->shouldCleanUpExpiredSessions = true;

        return true;
    }

    public function updateTimestamp($sessionId, $data)
    {
        // We need to implment this as AbstractSessionHandler uses \SessionUpdateTimestampHandlerInterface

        // The actual functionally is covered by how Symfony handles session data.
        // The session's data will change when it is older than session.metadata.update_threshold
        // and this is when we update the timestamps as well ensuring that sessions also get refreshed
        // even if no session data changes.
        return true;
    }

    /** @return bool */
    public function close()
    {
        $maxLifetime = (int) ini_get('session.gc_maxlifetime');

        if ($this->shouldCleanUpExpiredSessions) {
            $this->shouldCleanUpExpiredSessions = false;

            $sql = "DELETE FROM http_session WHERE sess_time < :time LIMIT 10000";
            $delete = $this->connection()->prepare($sql);
            $delete->bindValue(':time', time() + $maxLifetime, PDO::PARAM_INT);
            $delete->execute();
        }
        return true;
    }
}
