<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\FrontendBundle\Domain\Cell;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Region;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
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
        $regionFixture = $this->getRegionFixture();

        $expectedSection = new Section([
            'sectionId' => '123abc',
            'layoutElements' => [
                new LayoutElement(['layoutElementId' => 'cell_1']),
                new LayoutElement(['layoutElementId' => 'cell_2']),
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

    public function testMapArrayProperties()
    {
        $objectFixture = new \stdClass();
        $objectFixture->fooArray = [
            $this->getRegionFixture(),
        ];

        $actualObject = $this->mapper->map($objectFixture);

        $this->assertInstanceOf(Section::class, $actualObject->fooArray[0]);
    }

    /**
     * @return Region
     */
    private function getRegionFixture(): Region
    {
        return new Region([
            'regionId' => '123abc',
            'configuration' => new Region\Configuration([
                'flexDirection' => 'row',
            ]),
            'elements' => [
                new Cell(['cellId' => 'cell_1']),
                new Cell(['cellId' => 'cell_2']),
            ]
        ]);
    }
}
