<?php

namespace FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\SitemapService;
use Frontastic\Catwalk\FrontendBundle\Gateway\SitemapGateway;
use PHPUnit\Framework\TestCase;

class SitemapServiceTest extends TestCase
{
    /**
     * @var SitemapGateway|\Phake_IMock
     */
    private $sitemapGatewayMock;
    private SitemapService $sitemapService;

    public function setUp()
    {
        $this->sitemapGatewayMock = \Phake::mock(SitemapGateway::class);
        $this->sitemapService = new SitemapService($this->sitemapGatewayMock, []);
    }

    public function testCleanOutdatedWhenExistSingleSitemap()
    {
        \Phake::when($this->sitemapGatewayMock)->loadGenerationTimestampsByBasedir->thenReturn(
            $this->singleBasedirHasOutdatedFixture()
        );

        $this->sitemapService->cleanOutdated(3);

        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633948391);
        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633947670);
        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633947529);
        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633606717);
        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633606648);
    }

    public function testDontCleanOutdatedWhenNoneExists()
    {
        \Phake::when($this->sitemapGatewayMock)->loadGenerationTimestampsByBasedir->thenReturn(
            $this->noBasedirHasOutdatedFixture()
        );

        $this->sitemapService->cleanOutdated(3);

        \Phake::verify($this->sitemapGatewayMock, \Phake::never())->remove;

    }

    public function testCleanOutdatedInOnlyOneBasedirSitemap()
    {
        \Phake::when($this->sitemapGatewayMock)->loadGenerationTimestampsByBasedir->thenReturn(
            $this->multipleBasedirsOneHasOutdatedFixture()
        );

        $this->sitemapService->cleanOutdated(3);

        \Phake::verify($this->sitemapGatewayMock)->remove('sitemap/', 1633948391);

        \Phake::verifyNoFurtherInteraction($this->sitemapGatewayMock);
    }

    private function singleBasedirHasOutdatedFixture()
    {
        return [
            0 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949777,
            ],
            1 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949227,
            ],
            2 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633948497,
            ],
            3 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633948391,
            ],
            4 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633947670,
            ],
            5 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633947529,
            ],
            6 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633606717,
            ],
            7 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633606648,
            ],
        ];
    }

    private function noBasedirHasOutdatedFixture()
    {
        return [
            0 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949777,
            ],
            1 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949227,
            ],
        ];
    }

    private function multipleBasedirsOneHasOutdatedFixture()
    {
        return [
            0 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949777,
            ],
            1 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633949227,
            ],
            2 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633948497,
            ],
            3 => [
                'basedir' => 'sitemap/',
                'generationTimestamp' => 1633948391,
            ],
            4 => [
                'basedir' => 'another-sitemap/',
                'generationTimestamp' => 1633947670,
            ],
        ];
    }
}
