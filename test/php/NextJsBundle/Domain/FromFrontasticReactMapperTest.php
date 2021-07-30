<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\Cell;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Region;
use PHPUnit\Framework\TestCase;

class FromFrontasticReactMapperTest extends TestCase
{

    private FromFrontasticReactMapper $mapper;

    public function setUp()
    {
        $this->mapper = new FromFrontasticReactMapper();
    }

    public function testMapKnown()
    {

        $regionFixture = new Region([
            'regionId' => '123abc',
            'configuration' => new Region\Configuration([
                'flexDirection' => 'row',
            ]),
            'elements' => [
                new Cell(['cellId' => 'cell_1']),
                new Cell(['cellId' => 'cell_2']),
            ]
        ]);

        $expectedSection = new Section([
            'sectionId' => '123abc',
            'layoutElements' => [
                new Cell(['cellId' => 'cell_1']),
                new Cell(['cellId' => 'cell_2']),
            ]
        ]);

        $actualSection = $this->mapper->map($regionFixture);

        $this->assertEquals($expectedSection, $actualSection);
    }

    public function testMapUnknownClonesObject()
    {
        $objectFixture = new \stdClass();

        $actualObject = $this->mapper->map($objectFixture);

        $this->assertEquals($objectFixture, $actualObject);
    }

    public function testMapChildrenOfUnknownObjects()
    {
        $objectFixture = new \stdClass();
        $objectFixture->someChild = new \Frontastic\Catwalk\ApiCoreBundle\Domain\Context();

        $actualObject = $this->mapper->map($objectFixture);

        $this->assertInstanceOf(\stdClass::class, $actualObject);
        $this->assertInstanceOf(Context::class, $actualObject->someChild);
    }
}
