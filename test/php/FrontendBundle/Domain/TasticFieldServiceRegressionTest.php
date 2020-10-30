<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;

class TasticFieldServiceRegressionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TasticService|\PHPUnit\Framework\MockObject\MockObject
     */
    private $tasticDefinitionServiceMock;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var Node
     */
    private $node;

    public function setUp(): void
    {
        $this->tasticDefinitionServiceMock = $this->getMockBuilder(TasticService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context = new Context();
        $this->node = new Node();
    }

    private function mergeTwoRegressionProvider(array $dataOne, array $dataTwo)
    {
        /** @var Page $pageFixture */
        $pageFixture = $dataOne['pageFixture'];
        $tasticsOne = $pageFixture->regions[0]->elements[0]->tastics;

        /** @var Page $pageFixture */
        $pageFixture = $dataTwo['pageFixture'];
        $tasticsTwo = $pageFixture->regions[0]->elements[0]->tastics;

        return [
            'pageFixture' => $this->pageFixture(array_merge($tasticsOne, $tasticsTwo)),
            'streamFixtures' => array_merge($dataOne['streamFixtures'], $dataTwo['streamFixtures']),
            'tasticDefinitionFixture' => array_merge(
                $dataOne['tasticDefinitionFixture'],
                $dataTwo['tasticDefinitionFixture']
            ),
            'expectedResult' => array_merge(
                $dataOne['expectedResult'],
                $dataTwo['expectedResult']
            ),
        ];
    }

    private function provideTreeInGroupRegressionTestData(): array
    {
        $tasticDefinition = json_decode(
            file_get_contents(__DIR__ . '/_fixtures/tastic-field-service-regression/tree-in-group_tastic-schema.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-tree-in-group-tastic-id',
                'tasticType' => $tasticDefinition['tasticType'],
                'configuration' => new Tastic\Configuration([
                    'topCategories' => [[]],
                ]),
            ]),
        ]);

        $treeStreamFixture = [
            'tree' => ['children' => ['some-child'], 'some-node-information', 'some-additional-node-information'],
        ];

        $streamFixtures = [
            'tree' => [
                ['fieldValue' => ['node' => null, 'depth' => 1], 'returnValue' => $treeStreamFixture],
            ],
        ];

        $expected = [
            'some-tree-in-group-tastic-id' => [
                'topCategories' => [
                    [
                        'tree' => $treeStreamFixture,
                    ],
                ],
            ],
        ];

        return [
            'pageFixture' => $pageFixture,
            'streamFixtures' => $streamFixtures,
            'tasticDefinitionFixture' => [
                $tasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                    $tasticDefinition['schema']
                ),
            ],
            'expectedResult' => $expected,
        ];
    }

    private function provideBreadcrumbInGroupRegressionTestData(): array
    {
        $tasticDefinition = json_decode(
            file_get_contents(__DIR__ .
                '/_fixtures/tastic-field-service-regression/breadcrumb-in-group_tastic-schema.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-breadcrumb-in-group-tastic-id',
                'tasticType' => $tasticDefinition['tasticType'],
                'configuration' => new Tastic\Configuration([
                    'someGroup' => [[]],
                ]),
            ]),
        ]);

        $breadcrumbStreamFixtures = [
            'breadcrumb' => ['some-group-node', 'some-other-group-node', 'some-additional-group-node'],
        ];

        $streamFixtures = [
            'breadcrumb' => [
                ['fieldValue' => null, 'returnValue' => $breadcrumbStreamFixtures],
            ],
        ];

        $expected = [
            'some-breadcrumb-in-group-tastic-id' => [
                'someGroup' => [
                    [
                        'breadcrumb' => $breadcrumbStreamFixtures,
                    ],
                ],
            ],
        ];

        return [
            'pageFixture' => $pageFixture,
            'streamFixtures' => $streamFixtures,
            'tasticDefinitionFixture' => [
                $tasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                    $tasticDefinition['schema']
                ),
            ],
            'expectedResult' => $expected,
        ];
    }

    private function provideBreadcrumbInTopFieldsRegressionTestData(): array
    {
        $tasticDefinition = json_decode(
            file_get_contents(__DIR__ .
                '/_fixtures/tastic-field-service-regression/breadcrumb-in-top-fields_tastic-schema.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-breadcrumb-in-top-fields-tastic-id',
                'tasticType' => $tasticDefinition['tasticType'],
            ]),
        ]);

        $breadcrumbStreamFixtures = [
            'breadcrumb' => ['some-top-node', 'some-other-top-node', 'some-additional-top-node'],
        ];

        $streamFixtures = [
            'breadcrumb' => [
                ['fieldValue' => null, 'returnValue' => $breadcrumbStreamFixtures],
            ],
        ];

        $expected = [];
        $expected['some-breadcrumb-in-top-fields-tastic-id']['breadcrumb'] = $breadcrumbStreamFixtures;

        return [
            'pageFixture' => $pageFixture,
            'streamFixtures' => $streamFixtures,
            'tasticDefinitionFixture' => [
                $tasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                    $tasticDefinition['schema']
                ),
            ],
            'expectedResult' => $expected,
        ];
    }

    private function provideBreadcrumbRegressionTestData(): array
    {
        $tasticDefinition = json_decode(
            file_get_contents(__DIR__ . '/_fixtures/tastic-field-service-regression/breadcrumb_tastic-schema.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-breadcrumb-tastic-id',
                'tasticType' => $tasticDefinition['tasticType'],
            ]),
        ]);

        $breadcrumbStreamFixtures = [
            'breadcrumb' => ['some-node', 'some-other-node', 'some-additional-node'],
        ];

        $streamFixtures = [
            'breadcrumb' => [
                ['fieldValue' => null, 'returnValue' => $breadcrumbStreamFixtures],
            ],
        ];

        $expected = [];
        $expected['some-breadcrumb-tastic-id']['breadcrumb'] = $breadcrumbStreamFixtures;

        return [
            'pageFixture' => $pageFixture,
            'streamFixtures' => $streamFixtures,
            'tasticDefinitionFixture' => [
                $tasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                    $tasticDefinition['schema']
                ),
            ],
            'expectedResult' => $expected,
        ];
    }

    private function provideProductListRegressionTestData(): array
    {
        $productListTasticDefinition = json_decode(
            file_get_contents(__DIR__ .
                '/_fixtures/tastic-field-service-regression/custom-product-list_tastic-schema.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-tastic-id',
                'tasticType' => $productListTasticDefinition['tasticType'],
            ]),
        ]);

        $productListStreamFixtures = [
            'custom-product-list' => ['some-product', 'some-other-product'],
        ];

        $streamFixtures = [
            'custom-product-list' => [
                ['fieldValue' => null, 'returnValue' => $productListStreamFixtures],
            ],
        ];

        $expected = [];
        $expected['some-tastic-id']['stream'] = $productListStreamFixtures;

        return [
            'pageFixture' => $pageFixture,
            'streamFixtures' => $streamFixtures,
            'tasticDefinitionFixture' => [
                $productListTasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                    $productListTasticDefinition['schema']
                ),
            ],
            'expectedResult' => $expected,
        ];
    }

    public function provideRegressionTestData()
    {
        return [
            $this->provideProductListRegressionTestData(),
            $this->provideBreadcrumbRegressionTestData(),
            $this->mergeTwoRegressionProvider(
                $this->provideProductListRegressionTestData(),
                $this->provideBreadcrumbRegressionTestData()
            ),
            $this->provideBreadcrumbInTopFieldsRegressionTestData(),
            $this->provideBreadcrumbInGroupRegressionTestData(),
            $this->provideTreeInGroupRegressionTestData(),
        ];
    }

    /**
     * @param Page $pageFixture
     * @param array $streamFixtures
     * @param array $tasticDefinitionFixture
     * @param array $expectedResult
     *
     * @dataProvider provideRegressionTestData
     */
    public function testGetFieldDataIncludesDataRegressionTestCases(
        Page $pageFixture,
        array $streamFixtures,
        array $tasticDefinitionFixture,
        array $expectedResult
    ) {
        $fieldHandlerMocks = [];

        foreach ($streamFixtures as $type => $handleCalls) {
            $fieldHandler = $this->getMockBuilder(TasticFieldHandlerV2::class)
                ->disableOriginalConstructor()
                ->getMock();

            $fieldHandler->expects($this->any())
                ->method('getType')
                ->will($this->returnValue($type));

            foreach ($handleCalls as $handleCall) {
                $fieldHandler->expects($this->once())
                    ->method('handle')
                    ->with($this->context, $this->node, $pageFixture, $handleCall['fieldValue'])
                    ->will($this->returnValue($handleCall['returnValue']));
            }

            $fieldHandlerMocks[] = $fieldHandler;
        }

        $fieldService = new TasticFieldService(
            $this->tasticDefinitionServiceMock,
            $fieldHandlerMocks
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue($tasticDefinitionFixture));

        $actualResult = $fieldService->getFieldData($this->context, $this->node, $pageFixture);

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @param Page $pageFixture
     * @param array $streamFixtures
     * @param array $tasticDefinitionFixture
     * @param array $expectedResult
     *
     * @dataProvider provideRegressionTestData
     */
    public function testGetFieldDataIncludesDataRegressionWithOldFieldHandlerAdapterTestCases(
        Page $pageFixture,
        array $streamFixtures,
        array $tasticDefinitionFixture,
        array $expectedResult
    ) {
        $fieldHandlerMocks = [];

        foreach ($streamFixtures as $type => $handleCalls) {
            $fieldHandler = $this->getMockBuilder(TasticFieldHandler::class)
                ->disableOriginalConstructor()
                ->getMock();

            $fieldHandler->expects($this->any())
                ->method('getType')
                ->will($this->returnValue($type));

            foreach ($handleCalls as $handleCall) {
                $fieldHandler->expects($this->once())
                    ->method('handle')
                    ->with($this->context, $handleCall['fieldValue'])
                    ->will($this->returnValue($handleCall['returnValue']));
            }

            $fieldHandlerMocks[] = $fieldHandler;
        }

        $fieldService = new TasticFieldService(
            $this->tasticDefinitionServiceMock,
            $fieldHandlerMocks
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue($tasticDefinitionFixture));

        $actualResult = $fieldService->getFieldData($this->context, $this->node, $pageFixture);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function pageFixture(array $tastics): Page
    {
        return new Page([
            'regions' => [
                new Region([
                    'elements' => [
                        new Cell([
                            'tastics' => $tastics,
                        ]),
                    ],
                ]),
            ],
        ]);
    }

    private function tasticDefinitionFixture(array $schema): object
    {
        return (object)[
            'configurationSchema' => [
                'schema' => $schema,
            ],
        ];
    }
}
