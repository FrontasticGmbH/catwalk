<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Routing;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ProjectService;
use Frontastic\Catwalk\FrontendBundle\Routing\MasterLoader;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class MasterLoaderTest extends TestCase
{
    /**
     * @var ProjectService|MockObject
     */
    private $projectServiceMock;

    /**
     * @var LoggerInterface|MockObject
     */
    private $loggerMock;

    public function setUp(): void
    {
        $this->projectServiceMock = $this->getMockBuilder(ProjectService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();
    }

    /**
     * @param Project $project
     *
     * @dataProvider provideProjectFixture
     */
    public function testLoad(Project $project)
    {
        $this->projectServiceMock->expects($this->once())->method('getProject')
            ->will($this->returnValue($project));

        $masterLoader = new MasterLoader($this->projectServiceMock, $this->loggerMock);

        $resource = '';
        $routes = $masterLoader->load($resource);

        $this->assertInstanceOf(RouteCollection::class, $routes);

        /** @var Route $route */
        foreach ($routes->all() as $route) {
            $this->assertNotEmpty($route->getPath());
            $this->assertEmpty($route->getHost());
            $this->assertEquals(['GET'], $route->getMethods());
        }
    }

    public function provideProjectFixture()
    {
        return [
            'empty master routes' => [
                new Project(),
            ],
            'multiple master routes' => [
                new Project([
                    'configuration' => [
                        'masterRoutes' => [
                            [
                                "id" => "Product.view",
                                "path" => "/{url}/product/{identifier}",
                                "allowSlashInUrl" => true,
                            ],
                            [
                                "id" => "Checkout.cart",
                                "path" => "/checkout/cart",
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }
}
