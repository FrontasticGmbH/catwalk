<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class SiteBuilderPageServiceTest extends TestCase
{
    private vfsStreamDirectory $vfsRoot;
    private SiteBuilderPageService $siteBuilderPageService;

    public function setUp(): void
    {
        $this->vfsRoot = vfsStream::setup();

        $this->siteBuilderPageService = new SiteBuilderPageService(
            $this->vfsRoot->url()
        );
    }

    public function testRoutesToPathMap()
    {
        $routesFixture = require __DIR__ . '/_fixtures/routes_fixture.php';

        $this->siteBuilderPageService->storeSiteBuilderPagePathsFromRoutes($routesFixture);

        $this->assertEquals(
            require __DIR__ . '/_fixtures/expected_paths_map.php',
            require $this->vfsRoot->url() . '/' . SiteBuilderPageService::STORAGE_FILE
        );
    }

    public function testMatchKnownPath()
    {
        $this->injectPathMapFixture();

        $actualNodeId = $this->siteBuilderPageService->matchSiteBuilderPage('/herren/taschen', 'de_CH');

        $this->assertEquals(
            '2715283e5d7f2c1c2ad999a882012aa9',
            $actualNodeId
        );
    }

    public function testMatchUnknownPath()
    {
        $this->injectPathMapFixture();

        $actualNodeId = $this->siteBuilderPageService->matchSiteBuilderPage('/herren/taschen', 'de_LI');

        $this->assertNull($actualNodeId);
    }

    public function testAtomicUpdateOfCacheFile()
    {
        $routesFixture = require __DIR__ . '/_fixtures/routes_fixture.php';

        $this->siteBuilderPageService->storeSiteBuilderPagePathsFromRoutes($routesFixture);

        $this->assertFileExists($this->vfsRoot->url() . '/' . SiteBuilderPageService::STORAGE_FILE);

        $this->assertSame(1, count($this->vfsRoot->getChildren()), 'Temporary file was not removed');
    }

    private function injectPathMapFixture()
    {
        copy(
            __DIR__ . '/_fixtures/expected_paths_map.php',
            $this->vfsRoot->url() . '/' . SiteBuilderPageService::STORAGE_FILE
        );
    }
}
