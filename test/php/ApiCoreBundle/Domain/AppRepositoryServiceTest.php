<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\IntegrationTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @group integration
 */
class AppRepositoryServiceTest extends IntegrationTestCase
{
    protected function getService(): AppRepositoryService
    {
        return new AppRepositoryService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->getContainer()->get(\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway::class),
            $this->getContainer()->get('logger'),
            new Filesystem(),
            __DIR__
        );
    }

    protected function getBasicSchema(): array
    {
        return [
            'identifier' => 'testProduct',
            'name' => 'TestProduct',
            'fields' => [
                0 => [
                    'label' => 'Name',
                    'field' => 'name',
                ],
                1 => [
                    'label' => 'ID',
                    'field' => 'productId',
                ],
            ],
            'schema' => [
                0 => [
                    'name' => 'Base Data',
                    'fields' => [
                        0 => [
                            'label' => 'ID',
                            'field' => 'productId',
                            'type' => 'string',
                        ],
                        1 => [
                            'label' => 'Name',
                            'field' => 'name',
                            'type' => 'string',
                        ],
                        2 => [
                            'label' => 'Description',
                            'field' => 'description',
                            'type' => 'markdown',
                        ],
                    ],
                ],
            ],
            'indexes' => [
                0 => [
                    'name' => 'ID',
                    'fields' => ['productId'],
                ],
                1 => [
                    'name' => 'Name',
                    'fields' => ['productId', 'name'],
                ],
            ],
        ];
    }

    public function tearDown(): void
    {
        foreach (glob(__DIR__ . '/App/*.php') as $classFile) {
            unlink($classFile);
        }
    }

    public function testNewAppCreatesDataObject()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);

        $appRepositoryService->update($app);
        $test_product = new App\TestProduct();
        foreach (['dataId', 'sequence', 'productId', 'name', 'description'] as $property) {
            $this->assertTrue(property_exists($test_product, $property), "Expected property $property to exist on data object");
        }
    }

    public function testNewAppCreatesDatabaseTable()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);

        $appRepositoryService->update($app);

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->createSchemaManager();
        $columns = $schemaManager->listTableColumns('app_testproduct');
        $this->assertEquals(
            ['d_locale', 'd_id', 'd_sequence', 'd_is_deleted', 'd_productid', 'd_name', 'd_description'],
            array_keys($columns)
        );
    }

    public function testNewAppCreatesIndex()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);

        $appRepositoryService->update($app);

        // Index columns are read from MySQL directly; the DBAL index column API
        // differs between the 4.2 and the 4.4 leg.
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');
        $indexColumns = [];
        foreach ($connection->fetchAllAssociative('SHOW INDEX FROM app_testproduct') as $row) {
            $indexColumns[strtolower($row['Key_name'])][$row['Seq_in_index'] - 1] = $row['Column_name'];
        }
        $this->assertEquals(
            [
                'primary' => ['d_locale', 'd_id'],
                'idx_sequence' => ['d_locale', 'd_sequence'],
                'c_idx_id' => ['d_locale', 'd_productid'],
                'c_idx_name' => ['d_locale', 'd_productid', 'd_name'],
            ],
            $indexColumns
        );
    }

    /**
     * @depends testNewAppCreatesDatabaseTable
     */
    public function testOtherTablesStillExist()
    {
        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->createSchemaManager();
        $columns = $schemaManager->listTableColumns('app');
        $this->assertContains(
            'a_id',
            array_keys($columns)
        );
    }

    /**
     * This test has to be run in a different process since we change the class
     * definition. PHP cannot "undefine" an existing class thus we need a new
     * process.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testAppUpdateUpdatesSchema()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);
        $app->configurationSchema['schema'][0]['fields'][] = [
            'label' => 'Rating',
            'field' => 'rating',
            'type' => 'integer',
        ];

        $appRepositoryService->update($app);

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->createSchemaManager();
        $columns = $schemaManager->listTableColumns('app_testproduct');
        $this->assertEquals(
            ['d_locale', 'd_id', 'd_sequence', 'd_is_deleted', 'd_productid', 'd_name', 'd_description', 'd_rating'],
            array_keys($columns)
        );
    }

    /**
     * This test has to be run in a different process since we change the class
     * definition. PHP cannot "undefine" an existing class thus we need a new
     * process.
     *
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testNewUpdatesRemovesColumnAgain()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);

        $appRepositoryService->update($app);

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->createSchemaManager();
        $columns = $schemaManager->listTableColumns('app_testproduct');
        $this->assertEquals(
            ['d_locale', 'd_id', 'd_sequence', 'd_is_deleted', 'd_productid', 'd_name', 'd_description'],
            array_keys($columns)
        );
    }

    /**
     * @depends testNewAppCreatesDatabaseTable
     * @doesNotPerformAssertions Only prepares next test
     */
    public function testStoreEntity()
    {
        $appRepositoryService = $this->getService();
        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);
        $appRepositoryService->update($app);
        $repository = $appRepositoryService->getRepository('test_product');

        $test_product = new App\TestProduct([
            'locale' => 'en_GB',
            'dataId' => '42',
            'sequence' => '1',
            'productId' => '1',
            'name' => 'Wohlfühlsocken',
            'description' => 'So flauschig!',
        ]);

        $repository->store($test_product);
    }

    /**
     * @depends testStoreEntity
     */
    public function testFindEntity()
    {
        $appRepositoryService = $this->getService();
        $repository = $appRepositoryService->getRepository('test_product');

        $test_product = $repository->findOneByDataId('42');
        $this->assertSame('Wohlfühlsocken', $test_product->name);
    }

    public function testChangedGeneratorForcesRegenerationWithoutSequenceBump()
    {
        $app = new App([
            'appId' => 'app_1',
            'identifier' => 'testProduct',
            'sequence' => '000000000000000000000001',
            'configurationSchema' => $this->getBasicSchema(),
        ]);

        $appGateway = $this->createMock(\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway::class);
        $appGateway->method('getHighestSequence')->willReturn($app->sequence);
        $appGateway->method('getAll')->willReturn([$app]);

        $appRepositoryService = new AppRepositoryService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $appGateway,
            $this->getContainer()->get('logger'),
            new Filesystem(),
            __DIR__
        );

        // Fully synced environment (sequences match), but the files were
        // written by an older generator: the stored template hash differs.
        $entityFile = __DIR__ . '/App/TestProduct.php';
        file_put_contents($entityFile, "<?php // stale file from an old template\n");
        file_put_contents(__DIR__ . '/App/code.meta.php', '<?php return ' . var_export([
            '~highest_sequence' => $app->sequence,
            '~template_hash' => 'outdated-hash',
            'testProduct' => $app->sequence,
        ], true) . ';' . PHP_EOL);

        $appRepositoryService->getRepository('testProduct');

        $this->assertStringContainsString(
            '#[ORM\Entity',
            file_get_contents($entityFile),
            'Expected the stale entity file to be regenerated from the current template'
        );
        $metaData = include __DIR__ . '/App/code.meta.php';
        $this->assertNotEquals('outdated-hash', $metaData['~template_hash']);

        // A fresh service instance with matching hash, matching sequences
        // and a correctly stamped file must leave the file alone. Appending
        // to the file keeps the stamp intact.
        file_put_contents($entityFile, "// marker: must not be regenerated\n", FILE_APPEND);
        $appRepositoryService = new AppRepositoryService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $appGateway,
            $this->getContainer()->get('logger'),
            new Filesystem(),
            __DIR__
        );
        $appRepositoryService->getRepository('testProduct');

        $this->assertStringContainsString(
            'marker: must not be regenerated',
            file_get_contents($entityFile),
            'Expected no regeneration when template hash and sequences are unchanged'
        );
    }

    public function testFilesWrittenByPreUpgradeReleaseArePurgedAndRegenerated()
    {
        // Branch-switch / rollback scenario: a pre-upgrade release
        // regenerated the entity from its annotations-only template. It does
        // not know the ~template_hash key but round-trips it untouched, so
        // the meta data wrongly claims everything is in sync. Only the
        // missing stamp in the file itself gives the foreign file away.
        $app = $this->getSyncableTestProductApp();

        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');
        $entityFile = __DIR__ . '/App/TestProduct.php';
        $this->assertFileExists($entityFile);

        file_put_contents(
            $entityFile,
            "<?php\n// regenerated by a pre-upgrade release: docblock annotations only, no stamp\n"
        );

        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');

        $content = file_get_contents($entityFile);
        $this->assertStringContainsString(
            AppRepositoryService::TEMPLATE_HASH_STAMP,
            $content,
            'Expected the foreign entity file to be purged and regenerated'
        );
        $this->assertStringContainsString('#[ORM\Entity', $content);
    }

    public function testStaleFileOfUnknownAppIsPurgedWithoutRegeneration()
    {
        $app = $this->getSyncableTestProductApp();
        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');

        // Unstamped leftover of an app that no longer exists anywhere.
        $orphanFile = __DIR__ . '/App/OrphanRelic.php';
        file_put_contents($orphanFile, "<?php\n// left behind by a pre-upgrade release\n");

        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');

        $this->assertFileDoesNotExist($orphanFile);
        $this->assertFileExists(__DIR__ . '/App/TestProduct.php');
    }

    public function testDeletedEntityFileIsRegeneratedWithoutSequenceBump()
    {
        $app = $this->getSyncableTestProductApp();
        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');

        $entityFile = __DIR__ . '/App/TestProduct.php';
        unlink($entityFile);

        $this->getServiceWithGatewayFor($app)->getRepository('testProduct');

        $this->assertFileExists(
            $entityFile,
            'Expected the deleted entity file to be regenerated although the recorded sequence is current'
        );
    }

    public function testGeneratedFileCarriesBothMappingFormats()
    {
        $appRepositoryService = $this->getService();
        $appRepositoryService->update(new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]));

        $content = file_get_contents(__DIR__ . '/App/TestProduct.php');

        // Docblock annotations for pre-upgrade releases (annotation driver),
        // PHP attributes for current releases (attribute driver).
        $this->assertStringContainsString('@ORM\Entity', $content);
        $this->assertStringContainsString('#[ORM\Entity', $content);
        $this->assertStringContainsString('@ORM\Column(name="d_productid"', $content);
        $this->assertStringContainsString("#[ORM\Column(name: 'd_productid'", $content);

        // On PHP 7.4 a single-line attribute parses as a comment, a
        // multi-line attribute is a parse error — pre-upgrade releases
        // support PHP 7.4, so every attribute must stay on one line.
        foreach (explode("\n", $content) as $line) {
            if (strpos(ltrim($line), '#[') === 0) {
                $this->assertStringEndsWith(
                    ']',
                    rtrim($line),
                    "Attribute must be single-line for PHP 7.4 compatibility: $line"
                );
            }
        }
    }

    private function getSyncableTestProductApp(): App
    {
        return new App([
            'appId' => 'app_1',
            'identifier' => 'testProduct',
            'sequence' => '000000000000000000000001',
            'configurationSchema' => $this->getBasicSchema(),
        ]);
    }

    private function getServiceWithGatewayFor(App $app): AppRepositoryService
    {
        $appGateway = $this->createMock(\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway::class);
        $appGateway->method('getHighestSequence')->willReturn($app->sequence);
        $appGateway->method('getAll')->willReturn([$app]);

        return new AppRepositoryService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $appGateway,
            $this->getContainer()->get('logger'),
            new Filesystem(),
            __DIR__
        );
    }

    public function testSchemaIsUpdatedWhenEntityClassWasLoadedBefore()
    {
        $appRepositoryService = $this->getService();
        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->createSchemaManager();

        $app = new App([
            'appId' => 'app_1',
            'configurationSchema' => $this->getBasicSchema(),
        ]);
        $appRepositoryService->update($app);

        $this->assertEquals(
            ['d_locale', 'd_id', 'd_sequence', 'd_is_deleted', 'd_productid', 'd_name', 'd_description'],
            array_keys($schemaManager->listTableColumns('app_testproduct'))
        );

        // ensure the class is auto loaded
        new App\TestProduct();

        $app->configurationSchema['schema'][0]['fields'][] = [
            'label' => 'Additional Field',
            'field' => 'add',
            'type' => 'string',
        ];
        $appRepositoryService->update($app);

        $this->assertEquals(
            ['d_locale', 'd_id', 'd_sequence', 'd_is_deleted', 'd_productid', 'd_name', 'd_description', 'd_add'],
            array_keys($schemaManager->listTableColumns('app_testproduct'))
        );
    }
}
