<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;

class TasticFieldServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TasticFieldHandler|\PHPUnit\Framework\MockObject\MockObject
     */
    private $fieldHandlerMock;

    /**
     * @var TasticService|\PHPUnit\Framework\MockObject\MockObject
     */
    private $tasticDefinitionServiceMock;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var TasticFieldService
     */
    private $fieldService;

    public function setUp(): void
    {
        $this->fieldHandlerMock = $this->getMockBuilder(TasticFieldHandlerV2::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->fieldHandlerMock->expects($this->any())
            ->method('getType')
            ->will($this->returnValue('handled-type'));

        $this->tasticDefinitionServiceMock = $this->getMockBuilder(TasticService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->context = new Context();

        $this->fieldService = new TasticFieldService(
            $this->tasticDefinitionServiceMock,
            [$this->fieldHandlerMock]
        );
    }

    public function testGetFieldDataCallsHandlerForHandledFields()
    {
        $this->fieldHandlerMock->expects($this->once())
            ->method('handle')
            ->with(
                $this->isInstanceOf(Context::class),
                $this->isInstanceOf(Node::class),
                $this->isInstanceOf(Page::class),
                $this->equalTo('The Field Value')
            );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'handled-field' => 'The Field Value',
                ]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'handled-field',
                    'type' => 'handled-type',
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);
    }

    public function testGetFieldDataIncludesDataForHandledFields()
    {
        $this->fieldHandlerMock->expects($this->any())
            ->method('handle')
            ->will($this->returnValue('I handled this'));

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'handled-field' => 'The Field Value',
                ]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'handled-field',
                    'type' => 'handled-type',
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $actualResult = $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);

        $this->assertEquals(
            [
                'id-of-the-tastic' => [
                    'handled-field' => 'I handled this',
                ],
            ],
            $actualResult
        );
    }

    public function testGetFieldDataDoesNotCallHandlerForUnhandledFields()
    {
        $this->fieldHandlerMock->expects($this->never())
            ->method('handle');

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'handled-field' => 'The Field Value',
                ]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'unhandled-field',
                    'type' => 'unhandled-type',
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);
    }

    public function testGetFieldDataDoesNotIncludeDataForUnhandledFields()
    {
        $this->fieldHandlerMock->expects($this->any())
            ->method('handle')
            ->will($this->returnValue('I handled this'));

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'handled-field' => 'The Field Value',
                ]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'unhandled-field',
                    'type' => 'unhandled-type',
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $actualResult = $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);

        $this->assertEquals(
            [],
            $actualResult
        );
    }

    public function testGetFieldDataUsesDefaultValueForHandledFieldsWithoutValue()
    {
        $this->fieldHandlerMock->expects($this->once())
            ->method('handle')
            ->with(
                $this->isInstanceOf(Context::class),
                $this->isInstanceOf(Node::class),
                $this->isInstanceOf(Page::class),
                $this->equalTo('The Default Value')
            );

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'handled-field',
                    'type' => 'handled-type',
                    'default' => 'The Default Value',
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);
    }

    public function testGetFieldDataIgnoresEmptyGroups()
    {
        $this->fieldHandlerMock->expects($this->never())
            ->method('handle');

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'my-group',
                    'type' => 'group',
                    'fields' => [
                        [
                            'field' => 'handled-field',
                            'type' => 'handled-type',
                            'default' => 'The Default Value',
                        ],
                        [
                            'field' => 'unhandled-field',
                            'type' => 'unhandled-type',
                        ],
                    ],
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $actualResult = $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);

        $this->assertEquals(
            [],
            $actualResult
        );
    }

    public function testGetFieldDataRecursesIntoGroupsWithoutFields()
    {
        $this->fieldHandlerMock->expects($this->never())
            ->method('handle');

        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'my-group' => [
                        [],
                        [
                            'sub-group' => [
                                [],
                                [],
                            ],
                        ],
                    ],
                ]),
            ]),
        ]);

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'my-group',
                    'type' => 'group',
                    'fields' => [
                        [
                            'field' => 'unhandled-field',
                            'type' => 'unhandled-type',
                        ],
                        [
                            'field' => 'sub-group',
                            'type' => 'group',
                            'fields' => [],
                        ],
                    ],
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $actualResult = $this->fieldService->getFieldData($this->context, $this->nodeFixture(), $pageFixture);

        $this->assertEquals(
            [
                'id-of-the-tastic' => [
                    'my-group' => [
                        [],
                        [
                            'sub-group' => [
                                [],
                                [],
                            ],
                        ],
                    ],
                ],
            ],
            $actualResult
        );
    }

    public function testGetFieldDataRecursesIntoGroups()
    {
        $nodeFixture = $this->nodeFixture();
        $pageFixture = $this->pageFixture([
            new Tastic([
                'tasticId' => 'id-of-the-tastic',
                'tasticType' => 'a-tastic-type',
                'configuration' => new Tastic\Configuration([
                    'my-group' => [
                        [],
                        [
                            'unhandled-field' => 'Some Value',
                            'handled-field' => 'Config Value 1',
                            'sub-group' => [
                                [],
                                [
                                    'handled-sub-field' => 'Config Value 2',
                                ],
                                [],
                            ],
                        ],
                    ],
                ]),
            ]),
        ]);

        $this->fieldHandlerMock
            ->expects($this->exactly(5))
            ->method('handle')
            ->will($this->returnValueMap([
                [$this->context, $nodeFixture, $pageFixture, 'The Default Value', 'I handled this'],
                [$this->context, $nodeFixture, $pageFixture, 'The Sub-Group Default Value', 'I handled this sub-group'],
                [$this->context, $nodeFixture, $pageFixture, 'Config Value 1', 'I handled this Config Value 1'],
                [$this->context, $nodeFixture, $pageFixture, 'Config Value 2', 'I handled this Config Value 2'],
            ]));

        $tasticDefinitionFixture = $this->tasticDefinitionFixture(
            [
                [
                    'field' => 'my-group',
                    'type' => 'group',
                    'fields' => [
                        [
                            'field' => 'handled-field',
                            'type' => 'handled-type',
                            'default' => 'The Default Value',
                        ],
                        [
                            'field' => 'unhandled-field',
                            'type' => 'unhandled-type',
                        ],
                        [
                            'field' => 'sub-group',
                            'type' => 'group',
                            'fields' => [
                                [
                                    'field' => 'handled-sub-field',
                                    'type' => 'handled-type',
                                    'default' => 'The Sub-Group Default Value',
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->tasticDefinitionServiceMock->expects($this->any())
            ->method('getTasticsMappedByType')
            ->will($this->returnValue(['a-tastic-type' => $tasticDefinitionFixture]));

        $actualResult = $this->fieldService->getFieldData($this->context, $nodeFixture, $pageFixture);

        $this->assertEquals(
            [
                'id-of-the-tastic' => [
                    'my-group' => [
                        [
                            'handled-field' => 'I handled this',
                        ],
                        [
                            'handled-field' => 'I handled this Config Value 1',
                            'sub-group' => [
                                [
                                    'handled-sub-field' => 'I handled this sub-group',
                                ],
                                [
                                    'handled-sub-field' => 'I handled this Config Value 2',
                                ],
                                [
                                    'handled-sub-field' => 'I handled this sub-group',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            $actualResult
        );
    }

    private function nodeFixture(): Node
    {
        return new Node();
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

    private function tasticDefinitionFixture(array $fields): object
    {
        return (object)[
            'configurationSchema' => [
                'schema' => [
                    [
                        'fields' => $fields,
                    ],
                ],
            ],
        ];
    }
}
