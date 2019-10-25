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

    public function setUp()
    {
        $this->fieldHandlerMock = $this->getMockBuilder(TasticFieldHandler::class)
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
                $this->equalTo('The Field Value')
            );

        $pageFixture = $this->pageFixture([
            new Tastic([
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
                $this->equalTo('The Default Value')
            );

        $pageFixture = $this->pageFixture([
            new Tastic([
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
