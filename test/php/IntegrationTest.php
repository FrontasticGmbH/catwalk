<?php

namespace Frontastic\Catwalk;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Annotations\AnnotationRegistry;

abstract class IntegrationTest extends \PHPUnit\Framework\TestCase
{
    private static $container;

    private static $databaseFailure = false;

    protected static function getContainer(): ContainerInterface
    {
        AppKernel::$catwalkBaseDir = dirname(__DIR__);

        (new \Frontastic\Common\EnvironmentResolver())->loadEnvironmentVariables(
            [__DIR__ . '/../../../../', __DIR__ . '/../../', __DIR__ . '/../'],
            AppKernel::getBaseConfiguration()
        );

        if (!self::$container) {
            $kernel = new AppKernel('test', true);
            (new \Symfony\Component\Filesystem\Filesystem())->remove($kernel->getCacheDir());
            $kernel->boot();
            self::$container = $kernel->getContainer();
        }

        return self::$container;
    }

    public static function setupBeforeClass(): void
    {
        self::$container = null;
        self::$databaseFailure = false;

        self::initializeMysql(self::getContainer());
    }

    protected static function initializeMysql($container)
    {
        AnnotationRegistry::registerFile(__DIR__ . "/../../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");

        $connection = self::getContainer()->get('doctrine.dbal.default_connection');
        $parameters = $connection->getParams();
        $database = $parameters['dbname'];
        unset($parameters['dbname']);

        try {
            $tmpConnection = DriverManager::getConnection($parameters);
            $schemaManager = $tmpConnection->getSchemaManager();
            $schemaManager->dropAndCreateDatabase($database);
        } catch (\Exception $e) {
            self::$databaseFailure = "Could not connect to MySQL server: " . $e->getMessage();
            return;
        }

        /** @var EntityManager $entityManager */
        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');

        $entityMetaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->createSchema($entityMetaData);
    }

    public function setUp(): void
    {
        parent::setUp();

        if (self::$databaseFailure) {
            $this->markTestSkipped('Integration test skipped: ' . self::$databaseFailure);
        }

        $entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $entityManager->clear();
    }
}
