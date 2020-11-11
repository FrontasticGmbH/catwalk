<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Catwalk\IntegrationTest;

/**
 * @group integration
 */
class AppRepositoryServiceTest extends IntegrationTest
{
    protected function getService(): AppRepositoryService
    {
        return new AppRepositoryService(
            $this->getContainer()->get('doctrine.orm.entity_manager'),
            $this->getContainer()->get(\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway::class),
            $this->getContainer()->get('logger'),
            $this->getContainer()->get('filesystem'),
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

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();
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

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();
        $indexes = $schemaManager->listTableIndexes('app_testproduct');
        $this->assertEquals(
            [
                'primary' => ['d_locale', 'd_id'],
                'idx_sequence' => ['d_locale', 'd_sequence'],
                'c_idx_id' => ['d_locale', 'd_productid'],
                'c_idx_name' => ['d_locale', 'd_productid', 'd_name'],
            ],
            array_map(
                function (\Doctrine\DBAL\Schema\Index $index) {
                    return $index->getColumns();
                },
                $indexes
            )
        );
    }

    /**
     * @depends testNewAppCreatesDatabaseTable
     */
    public function testOtherTablesStillExist()
    {
        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();
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

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();
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

        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();
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

    public function testSchemaIsUpdatedWhenEntityClassWasLoadedBefore()
    {
        $appRepositoryService = $this->getService();
        $schemaManager = $this->getContainer()->get('doctrine.dbal.default_connection')->getSchemaManager();

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
