<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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

    public function setUp()
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

    private function provideBreadcrumbRegressionTestData(): array
    {
        $tasticDefinition = json_decode(
            file_get_contents(__DIR__ . '/regression-fixture-data/breadcrumb_tastic-schema.json'),
            true
        );

        $tasticConfiguration = json_decode(
            file_get_contents(__DIR__ . '/regression-fixture-data/breadcrumb_tastic-configuration.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-breadcrumb-tastic-id',
                'tasticType' => $tasticDefinition['tasticType'],
                'configuration' => (object)$tasticConfiguration,
            ])
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
            'tasticDefinitionFixture' => [$tasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                $tasticDefinition['schema'][0]['fields']
            )],
            'expectedResult' => $expected,
        ];
    }

    private function provideProductListRegressionTestData(): array
    {
        $productListTasticDefinition = json_decode(
            file_get_contents(__DIR__ . '/regression-fixture-data/custom-product-list_tastic-schema.json'),
            true
        );

        $productListTasticConfiguration = json_decode(
            file_get_contents(__DIR__ . '/regression-fixture-data/custom-product-list_tastic-configuration.json'),
            true
        );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'some-tastic-id',
                'tasticType' => $productListTasticDefinition['tasticType'],
                'configuration' => (object)$productListTasticConfiguration,
            ])
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
            'tasticDefinitionFixture' => [$productListTasticDefinition['tasticType'] => $this->tasticDefinitionFixture(
                $productListTasticDefinition['schema'][0]['fields']
            )],
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
            )
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
    )
    {
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

    private function pageFixture(array $tastics): Page
    {
        return new Page([
            'regions' => [
                new Region([
                    'elements' => [
                        new Cell([
                            'tastics' => $tastics,
                        ])
                    ]
                ])
            ]
        ]);
    }

    private function tasticDefinitionFixture(array $fields): object
    {
        return (object)[
            'configurationSchema' => [
                'schema' => [
                    [
                        'fields' => $fields,
                    ]
                ]
            ]
        ];
    }
}
