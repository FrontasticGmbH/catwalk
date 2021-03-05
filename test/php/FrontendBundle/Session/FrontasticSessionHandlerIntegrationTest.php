<?php

namespace Frontastic\Catwalk\FrontendBundle\Session;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Frontastic\Catwalk\IntegrationTest;

class FrontasticSessionHandlerIntegrationTest extends IntegrationTest
{
    protected const SESSION_ACTIVE_ID = 'test-session-active-id';
    protected const SESSION_ACTIVE_DATA = 'test-session-active-data';

    protected const SESSION_EXPIRED_ID = 'test-session-expired-id';
    protected const SESSION_EXPIRED_DATA = 'test-session-expired-data';

    protected const SESSION_NEW_ID = 'test-session-new-id';

    /** @var FrontasticSessionHandler */
    private $sessionHandler;

    protected static function getConnection(): Connection
    {
        /** @var EntityManager $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        return $entityManager->getConnection();
    }

    public static function setUpBeforeClass(): void
    {
        parent::setupBeforeClass();
        self::getConnection()->executeQuery('DROP TABLE IF EXISTS http_session');
        self::getConnection()->executeQuery(<<<SQL
CREATE TABLE `http_session` (
    `sess_id` varchar(128) NOT NULL,
    `sess_data` blob NOT NULL,
    `sess_time` int unsigned NOT NULL,
    `sess_lifetime` int DEFAULT NULL,
    PRIMARY KEY (`sess_id`),
    KEY `EXPIRY` (`sess_lifetime`)
) ENGINE=InnoDB
SQL
        );
    }

    public function setUp(): void
    {
        /** @var EntityManager $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $connection = $entityManager->getConnection();
        /** @noinspection SqlWithoutWhere */
        $connection->executeQuery('DELETE FROM http_session');

        $statement = $connection->prepare('INSERT INTO http_session VALUES (:id, :data, :time, :time)');
        $statement->execute([
            'id' => self::SESSION_ACTIVE_ID,
            'data' => self::SESSION_ACTIVE_DATA,
            'time' => time()
        ]);
        $statement->execute([
            'id' => self::SESSION_EXPIRED_ID,
            'data' => self::SESSION_EXPIRED_DATA,
            'time' => 1
        ]);

        $this->sessionHandler = new FrontasticSessionHandler(
            $connection,
            15
        );
    }

    public function testReadSession()
    {
        $this->assertSame(
            self::SESSION_ACTIVE_DATA,
            $this->sessionHandler->read(self::SESSION_ACTIVE_ID)
        );
    }

    public function testSessionGcRemovesExpiredSession()
    {
        $this->assertSame(
            self::SESSION_EXPIRED_DATA,
            $this->sessionHandler->read(self::SESSION_EXPIRED_ID),
            'Expired session does not exist initially, setup issue?'
        );
        $this->sessionHandler->gc(0);
        $this->sessionHandler->close();
        $this->assertSame(
            '',
            $this->sessionHandler->read(''),
            'Expired session did not get deleted'
        );
    }

    public function testSessionGcKeepsExistingSession()
    {
        $this->assertSame(
            self::SESSION_ACTIVE_DATA,
            $this->sessionHandler->read(self::SESSION_ACTIVE_ID),
            'Active session does not exist initially, setup issue?'
        );
        $this->sessionHandler->gc(0);
        $this->sessionHandler->close();
        $this->assertSame(
            self::SESSION_ACTIVE_DATA,
            $this->sessionHandler->read(self::SESSION_ACTIVE_ID),
            'Active session did get deleted'
        );
    }

    public function testWriteNewSession()
    {
        $newData = 'new-data';
        $this->sessionHandler->write(self::SESSION_NEW_ID, $newData);
        $this->assertSame(
            $newData,
            $this->sessionHandler->doRead(self::SESSION_NEW_ID),
            'Expired session did not get deleted'
        );
    }

    public function testUpdateExistingSession()
    {
        $this->assertSame(
            self::SESSION_ACTIVE_DATA,
            $this->sessionHandler->doRead(self::SESSION_ACTIVE_ID)
        );
        $newData = 'new-data';
        $this->sessionHandler->write(self::SESSION_ACTIVE_ID, $newData);
        $this->assertSame(
            $newData,
            $this->sessionHandler->doRead(self::SESSION_ACTIVE_ID)
        );
    }

    public function testDeleteSession()
    {
        $this->assertSame(
            self::SESSION_ACTIVE_DATA,
            $this->sessionHandler->doRead(self::SESSION_ACTIVE_ID)
        );
        $this->sessionHandler->destroy(self::SESSION_ACTIVE_ID);
        $this->assertSame(
            '',
            $this->sessionHandler->doRead(self::SESSION_ACTIVE_ID)
        );
    }
}
