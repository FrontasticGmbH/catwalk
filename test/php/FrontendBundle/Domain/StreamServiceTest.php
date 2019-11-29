<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;

class StreamServiceTest extends \PHPUnit\Framework\TestCase
{
    public function testRemoveUnusedStream()
    {
        $node = include __DIR__ . '/__fixtures/Node.php';
        $page = include __DIR__ . '/__fixtures/Page.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamService = new StreamService($tasticService, []);

        $parameters = [];
        $this->assertEquals(3, count($streamService->getUsedStreams($node, $page, $parameters)));
    }

    public function testSetCountParameters()
    {
        $node = include __DIR__ . '/__fixtures/Node.php';
        $page = include __DIR__ . '/__fixtures/Page.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamService = new StreamService($tasticService, []);

        $parameters = [];
        $streamService->getUsedStreams($node, $page, $parameters);

        $this->assertEquals(
            [
                'aabf67e7-8134-451e-85f5-4e069d0e41d4' => ['limit' => 12],
                '7440' => ['limit' => 7],
            ],
            $parameters
        );
    }
}
