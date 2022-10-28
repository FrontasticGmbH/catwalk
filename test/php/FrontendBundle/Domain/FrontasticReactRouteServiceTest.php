<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Catwalk\FrontendBundle\Gateway\FrontendRoutesGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FrontasticReactRouteServiceTest extends TestCase
{
    /**
     * @var RouteService
     */
    private $routeService;

    /**
     * @var CustomerService|MockObject
     */
    private $customerServiceMock;

    /**
     * @var FrontendRoutesGateway|MockObject
     */
    private $frontendRoutesGateway;

    public function setUp(): void
    {
        $this->customerServiceMock = $this->getMockBuilder(CustomerService::class)->disableOriginalConstructor()
            ->getMock();

        $this->frontendRoutesGateway = \Phake::mock(FrontendRoutesGateway::class);

        $this->routeService = new FrontasticReactRouteService(
            $this->customerServiceMock,
            '__NO_DIR__',
            $this->frontendRoutesGateway
        );

        $this->customerServiceMock->expects($this->any())
            ->method('getCustomer')
            ->will($this->returnValue(
                new Customer([
                    'projects' => [
                        new Project([
                            'defaultLanguage' => 'en_GB',
                            'languages' => [
                                'en_GB',
                                'de_DE',
                            ],
                        ]),
                    ],
                ])
            ));
    }

    public function testGetRoutesIsCalledWhenCacheRoutesOnDb() {
        putenv("database_routing=1");

        $frontendRoutes = new FrontendRoutes();
        $frontendRoutes->frontendRoutesId = 1;
        $frontendRoutes->frontendRoutes = [
            new Route([
                'nodeId' => 'n1',
                'route' => '/',
                'locale' => 'en_GB',
            ]),
        ];

        \Phake::when($this->frontendRoutesGateway)
            ->getById(FrontasticReactRouteService::CACHE_ID)
            ->thenReturn($frontendRoutes);

        $routes = $this->routeService->getRoutes();

        $this->assertNotEmpty($routes);
        $this->assertEquals($frontendRoutes->frontendRoutes, $routes);
    }

    public function testGetRoutesIsCalledWithException() {
        \Phake::when($this->frontendRoutesGateway)
            ->getById(FrontasticReactRouteService::CACHE_ID)
            ->thenThrow(new \OutOfBoundsException());

        $routes = $this->routeService->getRoutes();

        $this->assertEquals([], $routes);
    }

    public function testStoreRoutesIsCalledForTheFirstTimeWhenCacheRoutesOnDb() {
        putenv("database_routing=1");
        
        $routes = [
            new Route([
                'nodeId' => 'n1',
                'route' => '/',
                'locale' => 'en_GB',
            ]),
        ];

        $frontendRoutes = new FrontendRoutes();
        $frontendRoutes->frontendRoutesId = 1;
        $frontendRoutes->frontendRoutes = $routes;

        \Phake::when($this->frontendRoutesGateway)->getById(FrontasticReactRouteService::CACHE_ID)->thenReturn($frontendRoutes);

        $this->routeService->storeRoutes($routes);
        \Phake::verify($this->frontendRoutesGateway)->store($frontendRoutes);
    }

    public function testStoreRoutesIsCalledAfterFirstTimeWhenCacheRoutesOnDb() {
        putenv("database_routing=1");

        $routes = [
            new Route([
                'nodeId' => 'n1',
                'route' => '/',
                'locale' => 'en_GB',
            ]),
        ];

        $frontendRoutes = new FrontendRoutes();
        $frontendRoutes->frontendRoutesId = 1;
        $frontendRoutes->frontendRoutes = $routes;

        \Phake::when($this->frontendRoutesGateway)
            ->getById(FrontasticReactRouteService::CACHE_ID)
            ->thenThrow(new \OutOfBoundsException());

        $this->routeService->storeRoutes($routes);
        \Phake::verify($this->frontendRoutesGateway)->store($frontendRoutes);
    }

    public function testGenerateRoutesForNonTranslatedPaths()
    {
        $nodes = [];

        $nodes[] = new Node([
            'nodeId' => 'n1',
            'path' => '/',
            'configuration' => [
                'path' => '/',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => 'node2',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1',
            'configuration' => [
                'path' => 'node3',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n4',
            'path' => '/n1/n3',
            'configuration' => [
                'path' => 'node4',
            ],
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/node2',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/node2',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/node3',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/node3',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n4',
                    'route' => '/node3/node4',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n4',
                    'route' => '/node3/node4',
                    'locale' => 'de_DE',
                ]),
            ],
            $actualRoutes
        );
    }

    public function testGenerateRoutesForTranslatedPaths()
    {
        $nodes = [];

        $nodes[] = new Node([
            'nodeId' => 'n1',
            'path' => '/',
            'configuration' => [
                'path' => '/',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => 'fork',
                'pathTranslations' => [
                    'de_DE' => 'gabel',
                ],
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => 'knife',
                'pathTranslations' => [
                    'de_DE' => 'messer',
                ],
            ],
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/gabel',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/gabel/messer',
                    'locale' => 'de_DE',
                ]),
            ],
            $actualRoutes
        );
    }

    public function testGenerateRoutesForMissingTranslatedPaths()
    {
        $nodes = [];

        $nodes[] = new Node([
            'nodeId' => 'n1',
            'path' => '/',
            'configuration' => [
                'path' => '/',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => 'fork',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => 'knife',
                'pathTranslations' => [
                    'de_DE' => 'messer',
                ],
            ],
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/messer',
                    'locale' => 'de_DE',
                ]),
            ],
            $actualRoutes
        );
    }

    public function testGenerateRoutesForMixedTranslatedNonTranslatedPaths()
    {
        $nodes = [];

        $nodes[] = new Node([
            'nodeId' => 'n1',
            'path' => '/',
            'configuration' => [
                'path' => '/',
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => 'fork',
                'pathTranslations' => [
                    'de_DE' => 'gabel',
                ],
            ],
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => 'knife',
            ],
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/gabel',
                    'locale' => 'de_DE',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB',
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/gabel/knife',
                    'locale' => 'de_DE',
                ]),
            ],
            $actualRoutes
        );
    }
}
