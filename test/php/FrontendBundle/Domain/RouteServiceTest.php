<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use PHPUnit\Framework\TestCase;

class RouteServiceTest extends TestCase
{
    /**
     * @var RouteService
     */
    private $routeService;

    public function setUp()
    {
       $this->routeService = new RouteService('__NO_DIR__');
    }

    public function testGenerateRoutesForNonTranslatedRoutes()
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
                'n1' => new Route([
                    'nodeId' => 'n1',
                    'route' => '/',
                ]),
                'n2' => new Route([
                    'nodeId' => 'n2',
                    'route' => '/node2',
                ]),
                'n3' => new Route([
                    'nodeId' => 'n3',
                    'route' => '/node3',
                ]),
                'n4' => new Route([
                    'nodeId' => 'n4',
                    'route' => '/node3/node4',
                ])
            ],
            $actualRoutes
        );
    }
}
