<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CustomerService;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RouteServiceTest extends TestCase
{
    /**
     * @var RouteService
     */
    private $routeService;

    /**
     * @var CustomerService|MockObject
     */
    private $customerServiceMock;

    public function setUp()
    {
        $this->customerServiceMock = $this->getMockBuilder(CustomerService::class)->disableOriginalConstructor()
            ->getMock();

        $this->routeService = new RouteService($this->customerServiceMock, '__NO_DIR__');

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
                            ]
                        ])
                    ]
                ])
            ));
    }

    public function testGenerateRoutesForNonTranslatedPaths()
    {
        $nodes = [];

        $nodes[] = new Node([
            'nodeId' => 'n1',
            'path' => '/',
            'configuration' => [
                'path' => '/',
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => 'node2',
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1',
            'configuration' => [
                'path' => 'node3',
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n4',
            'path' => '/n1/n3',
            'configuration' => [
                'path' => 'node4',
            ]
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
                'path' => [
                    'en_GB' => '/',
                    'de_DE' => '/'
                ],
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => [
                    'en_GB' => 'fork',
                    'de_DE' => 'gabel'
                ],
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => [
                    'en_GB' => 'knife',
                    'de_DE' => 'messer'
                ],
            ]
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/gabel',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/gabel/messer',
                    'locale' => 'de_DE'
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
                'path' => [
                    'en_GB' => '/',
                ],
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => [
                    'en_GB' => 'fork',
                ],
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => [
                    'en_GB' => 'knife',
                    'de_DE' => 'messer'
                ],
            ]
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/messer',
                    'locale' => 'de_DE'
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
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n2',
            'path' => '/n1',
            'configuration' => [
                'path' => [
                    'en_GB' => 'fork',
                    'de_DE' => 'gabel'
                ],
            ]
        ]);
        $nodes[] = new Node([
            'nodeId' => 'n3',
            'path' => '/n1/n2',
            'configuration' => [
                'path' => 'knife',
            ]
        ]);

        $actualRoutes = $this->routeService->generateRoutes($nodes);

        $this->assertEquals(
            [
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n2',
                    'route' => '/fork',
                    'locale' => 'de_DE'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/knife',
                    'locale' => 'en_GB'
                ]),
                new Route([
                    'nodeId' => 'n3',
                    'route' => '/fork/messer',
                    'locale' => 'de_DE'
                ]),
            ],
            $actualRoutes
        );
    }
}
