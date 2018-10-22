<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway;

class PageServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var PageGateway
     */
    private $pageGatewayMock;

    public function setUp()
    {
        $this->pageGatewayMock = \Phake::mock(PageGateway::class);

        $this->pageService = new PageService($this->pageGatewayMock);
    }

    public function testReplicateDeleteWhenExists()
    {
        $fakePage = $this->fakePage();

        \Phake::when($this->pageGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenReturn($fakePage);

        $this->pageService->replicate([
            $this->fakePageData([
                'isDeleted' => true,
            ])
        ]);

        $expectedPage = clone $fakePage;
        $expectedPage->isDeleted = true;
        \Phake::verify($this->pageGatewayMock)->store($expectedPage);
    }

    public function testReplicateDeleteWhenAlreadyRemoved()
    {
        $fakePage = $this->fakePage(['isDeleted' => true]);

        \Phake::when($this->pageGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenReturn($fakePage);

        $this->pageService->replicate([
            $this->fakePageData([
                'isDeleted' => true,
            ])
        ]);

        \Phake::verify($this->pageGatewayMock)->store($fakePage);
    }

    public function testReplicateDeleteWhenNotExisted()
    {
        \Phake::when($this->pageGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenThrow(new \OutOfBoundsException());

        $this->pageService->replicate([
            $this->fakePageData([
                'isDeleted' => true,
            ])
        ]);

        $expectedPage = $this->fakePage(['isDeleted' => true]);
        $expectedPage->node = new Node();
        \Phake::verify($this->pageGatewayMock)->store($expectedPage);
    }

    private function fakePage(array $expliciteProperties = []): Page
    {
        $data = $this->fakePageData($expliciteProperties);

        $data['node'] = null;
        unset($data['nodes']);

        return new Page($data);
    }

    private function fakePageData(array $explicitData = []): array
    {
        $data = [];

        $data['pageId'] = '123abc';
        $data['sequence'] = '1111111111';
        $data['nodes'] = [];
        $data['layoutId'] = '123abc';
        $data['regions'] = [];
        $data['metaData'] = [];
        $data['isDeleted'] = false;

        return array_merge($data, $explicitData);
    }
}
