<?php

namespace Frontastic\Catwalk\FrontendBundle\Session;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
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
    public function doRead($sessionId): string
    {
        $query = 'SELECT sess_data, sess_time FROM http_session WHERE sess_id = :id';
        $result = $this->connection()->executeQuery(
            $query,
            ['id' => $sessionId]
        );

        $row = $result->fetchNumeric();
        if (!$row) {
            return '';
        }
        $this->sessions[$sessionId] = ['data' => $row[0], 'time' => $row[1]];
        return $row[0];
    }

    protected function doWrite($sessionId, $data): bool
    {
        if (isset($this->sessions[$sessionId])
            && $data === $this->sessions[$sessionId]['data']
            && time() > $this->sessions[$sessionId]['time'] - $this->writeThresholdInSeconds
        ) {
            return true;
        }

        $query = 'INSERT INTO http_session' .
            ' (sess_id, sess_data, sess_lifetime, sess_time)' .
            ' VALUES (:id, :data, :expiry, :time)' .
            ' ON DUPLICATE KEY UPDATE' .
            ' sess_data = VALUES(sess_data), sess_lifetime = VALUES(sess_lifetime), sess_time = VALUES(sess_time);'
        ;
        $maxLifetime = (int) ini_get('session.gc_maxlifetime');
        $this->connection()->executeStatement(
            $query,
            [
                'id' => $sessionId,
                'data' => $data,
                'time' => time(),
                'expiry' => time() + $maxLifetime,
            ],
            [
                'data' => ParameterType::LARGE_OBJECT,
                'time' => ParameterType::INTEGER,
                'expiry' => ParameterType::INTEGER,
            ]
        );

        return true;
    }

    protected function doDestroy($sessionId): bool
    {
        $query = 'DELETE FROM http_session WHERE sess_id = :id';
        $this->connection()->executeStatement(
            $query,
            ['id' => $sessionId]
        );

        return true;
    }

    /** @noinspection PhpParameterNameChangedDuringInheritanceInspection */
    public function gc($maxLifetime): int
    {
        $this->shouldCleanUpExpiredSessions = true;

        return 0;
    }

    /** @noinspection PhpParameterNameChangedDuringInheritanceInspection */
    public function updateTimestamp($sessionId, $data): bool
    {
        // We need to implement this as AbstractSessionHandler uses \SessionUpdateTimestampHandlerInterface

        // The actual functionally is covered by how Symfony handles session data.
        // The session's data will change when it is older than session.metadata.update_threshold
        // and this is when we update the timestamps as well ensuring that sessions also get refreshed
        // even if no session data changes.
        return true;
    }

    /** @return bool */
    public function close(): bool
    {
        $maxLifetime = (int) ini_get('session.gc_maxlifetime');

        if ($this->shouldCleanUpExpiredSessions) {
            $this->shouldCleanUpExpiredSessions = false;

            $sql = 'DELETE FROM http_session WHERE sess_time < :time LIMIT 10000';
            $this->connection()->executeStatement(
                $sql,
                ['time' => time() - $maxLifetime],
                ['time' => ParameterType::INTEGER]
            );
        }
        return true;
    }
}
