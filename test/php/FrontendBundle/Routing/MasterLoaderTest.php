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
     * @dataProvider provideProjectFixtures
     */
    public function testLoad(Project $project)
    {
        $this->projectServiceMock->expects($this->once())->method('getProject')
            ->will($this->returnValue($project));

        $masterLoader = new MasterLoader($this->projectServiceMock, $this->loggerMock);

        $routes = $masterLoader->load('');

        $this->assertInstanceOf(RouteCollection::class, $routes);

        /** @var Route $route */
        foreach ($routes->all() as $route) {
            $this->assertNotEmpty($route->getPath());
            $this->assertEmpty($route->getHost());
            $this->assertEquals(['GET'], $route->getMethods());
            $this->assertNotEmpty($route->getDefault('_controller'));
        }
    }

    public function testLoadWithInvalidIdsAndPaths()
    {
        $project = new Project([
            'configuration' => [
                'masterRoutes' => [
                    [
                        "id" => "id.non.existing",
                        "path" => "/{url}/product/{identifier}",
                    ],
                    [
                        "id" => "Product.view",
                    ],
                    [
                        "path" => "/{url}/product/{identifier}",
                    ],
                ],
            ],
        ]);

        $this->projectServiceMock->expects($this->once())->method('getProject')
            ->will($this->returnValue($project));

        $masterLoader = new MasterLoader($this->projectServiceMock, $this->loggerMock);

        $routes = $masterLoader->load('');

        $this->assertInstanceOf(RouteCollection::class, $routes);

        $this->assertEmpty($routes->all());
    }

    /**
     * @param Project $project
     *
     * @dataProvider provideSingleProjectFixtures
     */
    public function testLoadAreWellFormed(Project $project)
    {
        $this->projectServiceMock->expects($this->once())->method('getProject')
            ->will($this->returnValue($project));

        $masterLoader = new MasterLoader($this->projectServiceMock, $this->loggerMock);

        $routes = $masterLoader->load('');
        $this->assertInstanceOf(RouteCollection::class, $routes);

        /** @var Route $route */
        foreach ($routes->all() as $key => $route) {
            $this->assertSame(
                MasterLoader::MASTER_ROUTE_ID . '.' . $project->configuration['masterRoutes'][0]['id'],
                $key
            );
            $this->assertSame($project->configuration['masterRoutes'][0]['path'], $route->getPath());
            if ($project->configuration['masterRoutes'][0]['allowSlashInUrl'] ?? false) {
                $this->assertSame('.+', $route->getRequirement('url'));
            } else {
                $this->assertEmpty($route->getRequirement('url'));
            }
        }

    }

    public function provideProjectFixtures()
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

    public function provideSingleProjectFixtures()
    {
        return [
            'Product master route' => [
                new Project([
                    'configuration' => [
                        'masterRoutes' => [
                            [
                                "id" => "Product.view",
                                "path" => "/{url}/product/{identifier}",
                                "allowSlashInUrl" => true,
                            ],
                        ],
                    ],
                ]),
            ],
            'Cart master route' => [
                new Project([
                    'configuration' => [
                        'masterRoutes' => [
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
