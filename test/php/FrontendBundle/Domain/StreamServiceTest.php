<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use GuzzleHttp\Promise;
use Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

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

    public function testRemoveUnusedStreamMindingGroupStreams()
    {
        $node = include __DIR__ . '/__fixtures/GroupNode.php';
        $page = include __DIR__ . '/__fixtures/GroupPage.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamService = new StreamService($tasticService, []);

        $parameters = [];
        $this->assertEquals(4, count($streamService->getUsedStreams($node, $page, $parameters)));
    }

    public function testSetCountParametersFromGroup()
    {
        $node = include __DIR__ . '/__fixtures/GroupNode.php';
        $page = include __DIR__ . '/__fixtures/GroupPage.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamService = new StreamService($tasticService, []);

        $parameters = [];
        $streams = $streamService->getUsedStreams($node, $page, $parameters);

        $this->assertEquals(
            [
                'aabf67e7-8134-451e-85f5-4e069d0e41d4' => ['limit' => 12],
                '7440' => ['limit' => 7],
                '70b37120-b446-4d11-b441-b6a80fabb48a' => ['limit' => 12],
            ],
            $parameters
        );
    }

    public function testGetTasticsUsingStreams()
    {
        $node = include __DIR__ . '/__fixtures/GroupNode.php';
        $page = include __DIR__ . '/__fixtures/GroupPage.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamService = new StreamService($tasticService, []);

        $parameters = [];
        $streams = $streamService->getUsedStreams($node, $page, $parameters);

        $this->assertEquals(
            [
                ['70b37120-b446-4d11-b441-b6a80fabb48a' => ['test-tastic']],
                ['d9c9bea5-ad6e-4e6a-a516-f1ecd46c66b0' => ['frontastic/boost/brand-contentful']],
                ['aabf67e7-8134-451e-85f5-4e069d0e41d4' => ['frontastic/boost/product-slider', 'test-tastic']],
                ['7440' => ['frontastic/boost/product-slider']],
            ],
            array_map(
                function (array $stream): array {
                    return [
                        $stream['streamId'] => array_map(
                            function ($tastic): string {
                                return $tastic->tasticType;
                            },
                            $stream['tastics']
                        )
                    ];
                },
                $streams
            )
        );
    }

    public function testGetStreamData()
    {
        $node = include __DIR__ . '/__fixtures/GroupNode.php';
        $page = include __DIR__ . '/__fixtures/GroupPage.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamHandler = \Phake::mock(StreamHandler::class);
        \Phake::when($streamHandler)->getType()->thenReturn('product-list');
        \Phake::when($streamHandler)->handleAsync(\Phake::anyParameters())->thenReturn(Promise\promise_for('some data'));

        $streamService = new StreamService($tasticService, [$streamHandler]);

        $streamData = $streamService->getStreamData($node, new Context(), [], $page);

        $this->assertEquals(
            [
                '70b37120-b446-4d11-b441-b6a80fabb48a' => 'some data',
                'd9c9bea5-ad6e-4e6a-a516-f1ecd46c66b0' => ['ok' => false, 'message' => 'No stream handler for stream type content configured.'],
                'aabf67e7-8134-451e-85f5-4e069d0e41d4' => 'some data',
                '7440' => 'some data',
            ],
            $streamData
        );
    }

    public function testOptimizeStreamData()
    {
        $node = include __DIR__ . '/__fixtures/GroupNode.php';
        $page = include __DIR__ . '/__fixtures/GroupPage.php';
        $tastics = include __DIR__ . '/__fixtures/tastics.php';

        $tasticService = \Phake::mock(TasticService::class);
        \Phake::when($tasticService)->getTasticsMappedByType()->thenReturn($tastics);

        $streamHandler = \Phake::mock(StreamHandler::class);
        \Phake::when($streamHandler)->getType()->thenReturn('product-list');
        \Phake::when($streamHandler)->handleAsync(\Phake::anyParameters())->thenReturn(Promise\promise_for('some data'));

        $streamOptimizer = \Phake::mock(StreamOptimizer::class);
        \Phake::when($streamOptimizer)->optimizeStreamData(\Phake::anyParameters())->thenReturn('optimized data');

        $streamService = new StreamService($tasticService, [$streamHandler], [$streamOptimizer]);

        $streamData = $streamService->getStreamData($node, new Context(), [], $page);

        $this->assertEquals(
            [
                '70b37120-b446-4d11-b441-b6a80fabb48a' => 'optimized data',
                'd9c9bea5-ad6e-4e6a-a516-f1ecd46c66b0' => 'optimized data',
                'aabf67e7-8134-451e-85f5-4e069d0e41d4' => 'optimized data',
                '7440' => 'optimized data',
            ],
            $streamData
        );
    }
}
