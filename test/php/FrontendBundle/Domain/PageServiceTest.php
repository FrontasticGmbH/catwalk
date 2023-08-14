<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway;
use RulerZ\RulerZ;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class PageServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @var RulerZ
     */
    private $rulerZMock;

    /**
     * @var PageGateway
     */
    private $pageGatewayMock;

    /**
     * @var TrackingService
     */
    private $trackingServiceMock;

    public function setUp(): void
    {
        $this->pageGatewayMock = \Phake::mock(PageGateway::class);
        $this->rulerZMock = \Phake::mock(RulerZ::class);
        $this->trackingServiceMock = \Phake::mock(TrackingService::class);

        $this->pageService = new PageService($this->pageGatewayMock, $this->rulerZMock, $this->trackingServiceMock);
    }

    public function testReplicateDeleteWhenExists()
    {
        $fakePage = $this->fakePage();

        \Phake::when($this->pageGatewayMock)->getEvenIfDeleted(\Phake::anyParameters())->thenReturn($fakePage);

        $this->pageService->replicate([
            $this->fakePageData([
                'isDeleted' => true,
            ]),
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
            ]),
        ]);

        \Phake::verify($this->pageGatewayMock)->store($fakePage);
    }

    public function testReplicateDeleteWhenNotExisted()
    {
        \Phake::when($this->pageGatewayMock)
            ->getEvenIfDeleted(\Phake::anyParameters())
            ->thenThrow(new \OutOfBoundsException());

        $this->pageService->replicate([
            $this->fakePageData([
                'isDeleted' => true,
            ]),
        ]);

        $expectedPage = $this->fakePage(['isDeleted' => true]);
        $expectedPage->node = '';
        \Phake::verify($this->pageGatewayMock)->store($expectedPage);
    }

    private function getPage(string $name, ?string $experiment = null, ?string $criterion = null): Page
    {
        return new Page([
            'pageId' => $name,
            'scheduledExperiment' => $experiment,
            'scheduleCriterion' => $criterion,
        ]);
    }

    public function getPageCandidates()
    {
        return array(
            [[$this->getPage('default')], 'default'],
            [[$this->getPage('criterion', null, 'false'), $this->getPage('default')], 'default'],
            [[$this->getPage('criterion', null, 'true'), $this->getPage('default')], 'criterion'],
            [[$this->getPage('experiment', 'active'), $this->getPage('default')], 'experiment'],
            [[$this->getPage('experiment', 'inactive'), $this->getPage('default')], 'default'],
            [[$this->getPage('a-f', 'active', 'false'), $this->getPage('criterion', null, 'true'), $this->getPage('default')], 'criterion'],
            [[$this->getPage('i-t', 'inactive', 'true'), $this->getPage('criterion', null, 'true'), $this->getPage('default')], 'criterion'],
            [[$this->getPage('a-t', 'active', 'true'), $this->getPage('criterion', null, 'true'), $this->getPage('default')], 'a-t'],
            [[$this->getPage('i-t', 'inactive', 'true'), $this->getPage('criterion', null, 'false'), $this->getPage('experiment', 'inactive'), $this->getPage('default')], 'default'],
        );
    }

    /**
     * @dataProvider getPageCandidates
     */
    public function testFetchForNode(array $candidates, string $winner)
    {
        $context = ['locale' => 'en', 'host' => 'example.com'];

        \Phake::when($this->pageGatewayMock)
            ->fetchForNode(\Phake::anyParameters())
            ->thenReturn($candidates);

        \Phake::when($this->rulerZMock)
            ->satisfies($context, 'true')
            ->thenReturn(true);
        \Phake::when($this->rulerZMock)
            ->satisfies($context, 'false')
            ->thenReturn(false);

        \Phake::when($this->trackingServiceMock)
            ->shouldRunExperiment('active')
            ->thenReturn(true);
        \Phake::when($this->trackingServiceMock)
            ->shouldRunExperiment('inactive')
            ->thenReturn(false);

        $page = $this->pageService->fetchForNode(new Node(['nodeId' => 42]), new Context($context));
        $this->assertEquals($page->pageId, $winner);
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
        $data['state'] = 'default';

        return array_merge($data, $explicitData);
    }
}
