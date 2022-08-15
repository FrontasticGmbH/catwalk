<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Catwalk\FrontendBundle\Gateway\NodeGateway;

class NodeServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var NodeService
     */
    private $nodeService;

    /**
     * @var NodeGateway
     */
    private $nodeGatewayMock;

    /**
     * @var PageService
     */
    private $pageServiceMock;

    /**
     * @var RouteService
     */
    private $routeServiceMock;

    /**
     * @var SchemaService
     */
    private $schemaServiceMock;

    /**
     * @var ContextService
     */
    private $contextServiceMock;

    public function setUp(): void
    {
        $this->nodeGatewayMock = \Phake::mock(NodeGateway::class);
        $this->pageServiceMock = \Phake::mock(PageService::class);
        $this->routeServiceMock = \Phake::mock(RouteService::class);
        $this->schemaServiceMock = \Phake::mock(SchemaService::class);
        $this->contextServiceMock = \Phake::mock(ContextService::class);

        $this->nodeService = new NodeService(
            $this->nodeGatewayMock,
            $this->pageServiceMock,
            $this->routeServiceMock,
            $this->schemaServiceMock,
            $this->contextServiceMock
        );
    }

    public function testReplicateDeleteWhenExists()
    {
        $fakeNode = $this->fakeNode();

        \Phake::when($this->nodeGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenReturn($fakeNode);

        $this->nodeService->replicate([
            $this->fakeNodeData([
                'isDeleted' => true,
            ])
        ]);

        $expectedNode = clone $fakeNode;
        $expectedNode->isDeleted = true;
        \Phake::verify($this->nodeGatewayMock)->store($expectedNode);
    }

    public function testReplicateDeleteWhenAlreadyRemoved()
    {
        $fakeNode = $this->fakeNode(['isDeleted' => true]);

        \Phake::when($this->nodeGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenReturn($fakeNode);

        $this->nodeService->replicate([
            $this->fakeNodeData([
                'isDeleted' => true,
            ])
        ]);

        \Phake::verify($this->nodeGatewayMock)->store($fakeNode);
    }

    public function testReplicateDeleteWhenNotExisted()
    {
        \Phake::when($this->nodeGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenThrow(new \OutOfBoundsException());

        $this->nodeService->replicate([
            $this->fakeNodeData([
                'isDeleted' => true,
            ])
        ]);

        $expectedNode = $this->fakeNode(['isDeleted' => true]);
        \Phake::verify($this->nodeGatewayMock)->store($expectedNode);
    }

    private function fakeNode(array $expliciteProperties = []): Node
    {
        $data = $this->fakeNodeData($expliciteProperties);
        $data['depth'] = count($data['path']);
        $data['path'] = '/' . implode('/', $data['path']);

        return new Node($data);
    }

    private function fakeNodeData(array $explicitData = []): array
    {
        $data = [];

        $data['nodeId'] = '123abc';
        $data['sequence'] = '4711';
        $data['isMaster'] = false;
        $data['configuration'] = [];
        $data['streams'] = [];
        $data['name'] = 'Test Node';
        $data['path'] = ['foo', 'bar'];
        $data['sort'] = 1;
        $data['metaData'] = [];
        $data['isDeleted'] = false;

        return array_merge($data, $explicitData);
    }
}
