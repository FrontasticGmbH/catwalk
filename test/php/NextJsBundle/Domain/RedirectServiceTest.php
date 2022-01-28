<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;
use Frontastic\Catwalk\FrontendBundle\Domain\RedirectService as FrontasticReactRedirectService;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\RedirectResponse;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use Symfony\Component\HttpFoundation\ParameterBag;

class RedirectServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var FrontasticReactRedirectService|\Phake_IMock
     */
    private $reactRedirectServiceMock;
    /**
     * @var SiteBuilderPageService|\Phake_IMock
     */
    private $siteBuilderPageServiceMock;

    /**
     * @var RedirectService
     */
    private $redirectService;

    /**
     * @var Context
     */
    private $contextFixture;

    /**
     * @var Redirect
     */
    private $pageFolderRedirect;

    /**
     * @var Redirect
     */
    private $linkRedirect;

    public function setUp(): void
    {
        $this->reactRedirectServiceMock = \Phake::mock(FrontasticReactRedirectService::class);
        $this->siteBuilderPageServiceMock = \Phake::mock(SiteBuilderPageService::class);

        $this->setupSitebuilderServiceMocks();
        $this->setupRedirects();
        $this->setupReactRedirectServiceMocks();

        $this->contextFixture = new Context([
            'project' => new Project([
                'languages' => [
                    'en_GB',
                    'en_US'
                ]
            ]),
            'locale' => 'en_GB',
        ]);

        $this->redirectService = new RedirectService(
            $this->reactRedirectServiceMock,
            $this->siteBuilderPageServiceMock
        );
    }

    protected function setupSitebuilderServiceMocks()
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage('/page-us', 'en_US')->thenReturn('1');
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage('/page-gb', 'en_GB')->thenReturn('1');

        \Phake::when($this->siteBuilderPageServiceMock)->getPathsForSiteBuilderPage('1')->thenReturn([
            'en_US' => '/page-us',
            'en_GB' => '/page-gb'
        ]);
    }

    protected function setupRedirects()
    {
        $this->pageFolderRedirect = new Redirect([
            'statusCode' => 301,
            'redirectId' => '1',
            'path' => '/redirect-to-page-folder',
            'targetType' => Redirect::TARGET_TYPE_NODE,
            'target' => '1',
        ]);

        $this->linkRedirect = new Redirect([
            'statusCode' => 301,
            'redirectId' => '2',
            'path' => '/redirect-to-link',
            'targetType' => Redirect::TARGET_TYPE_LINK,
            'target' => 'https://frontastic.cloud'
        ]);
    }

    protected function setupReactRedirectServiceMocks()
    {
        \Phake::when($this->reactRedirectServiceMock)
            ->getRedirectForRequest
            ->thenReturn(null);

        \Phake::when($this->reactRedirectServiceMock)
            ->getRedirectForRequest($this->pageFolderRedirect->path, new ParameterBag())
            ->thenReturn($this->pageFolderRedirect);

        \Phake::when($this->reactRedirectServiceMock)
            ->getRedirectForRequest($this->linkRedirect->path, new ParameterBag())
            ->thenReturn($this->linkRedirect);
    }

    public function testGetLocaleMismatchRedirectWithCorrectLocale()
    {
        $redirectResponse = $this->redirectService->getLocaleMismatchRedirect('/page-gb', 'en_GB', ['en_GB', 'en_US']);

        $this->assertNull($redirectResponse);
    }

    public function testGetLocaleMismatchRedirectWithIncorrectLocale()
    {
        $redirectResponse = $this->redirectService->getLocaleMismatchRedirect('/page-gb', 'en_US', ['en_GB', 'en_US']);

        $this->assertInstanceOf(RedirectResponse::class, $redirectResponse);
        $this->assertEquals('/page-us', $redirectResponse->target);
        $this->assertEquals(RedirectResponse::REASON_LOCALE_MISMATCH, $redirectResponse->reason);
    }

    public function testGetLocaleMismatchRedirectWithUnsupportedLocale()
    {
        $redirectResponse = $this->redirectService->getLocaleMismatchRedirect('/page-gb', 'de_CH', ['en_GB', 'en_US']);

        $this->assertNull($redirectResponse);
    }

    public function testGetLocaleMismatchRedirectWithNonExistentPath()
    {
        $redirectResponse = $this->redirectService->getLocaleMismatchRedirect('/page-non-existent', 'en_GB', ['en_GB', 'en_US']);

        $this->assertNull($redirectResponse);
    }

    public function testCreateResponseFromRedirectObjectToPageFolder()
    {
        $redirectResponse = $this->redirectService->createResponseFromRedirectObject($this->pageFolderRedirect, 'en_GB');

        $this->assertInstanceOf(RedirectResponse::class, $redirectResponse);
        $this->assertEquals('/page-gb', $redirectResponse->target);
        $this->assertEquals(RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH, $redirectResponse->reason);
        $this->assertEquals(RedirectResponse::TARGET_TYPE_PAGE_FOLDER, $redirectResponse->targetType);
        $this->assertEquals($this->pageFolderRedirect->statusCode, $redirectResponse->statusCode);
    }

    public function testCreateResponseFromRedirectObjectToLink()
    {
        $redirectResponse = $this->redirectService->createResponseFromRedirectObject($this->linkRedirect, 'en_GB');

        $this->assertInstanceOf(RedirectResponse::class, $redirectResponse);
        $this->assertEquals($this->linkRedirect->target, $redirectResponse->target);
        $this->assertEquals(RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH, $redirectResponse->reason);
        $this->assertEquals(RedirectResponse::TARGET_TYPE_LINK, $redirectResponse->targetType);
        $this->assertEquals($this->linkRedirect->statusCode, $redirectResponse->statusCode);
    }

    public function testCreateResponseFromRedirectObjectToPageFolderWithUnsupportedLocale()
    {
        $redirectResponse = $this->redirectService->createResponseFromRedirectObject($this->pageFolderRedirect, 'de_CH');

        $this->assertNull($redirectResponse);
    }

    public function testGetRedirectResponseForPathWithNoRedirects()
    {
        $redirectResponse = $this->redirectService->getRedirectResponseForPath('/page-gb', [], $this->contextFixture);

        $this->assertNull($redirectResponse);
    }

    public function testGetRedirectResponseForPathWithLocaleMismatch()
    {
        $redirectResponse = $this->redirectService->getRedirectResponseForPath('/page-us', [], $this->contextFixture);

        $this->assertInstanceOf(RedirectResponse::class, $redirectResponse);
        $this->assertEquals('/page-gb', $redirectResponse->target);
        $this->assertEquals(RedirectResponse::REASON_LOCALE_MISMATCH, $redirectResponse->reason);
    }

    public function testGetRedirectResponseForPathWithExistingRedirect()
    {
        $redirectResponse = $this->redirectService->getRedirectResponseForPath($this->pageFolderRedirect->path, [], $this->contextFixture);

        $this->assertInstanceOf(RedirectResponse::class, $redirectResponse);
        $this->assertEquals('/page-gb', $redirectResponse->target);
        $this->assertEquals(RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH, $redirectResponse->reason);
        $this->assertEquals(RedirectResponse::TARGET_TYPE_PAGE_FOLDER, $redirectResponse->targetType);
        $this->assertEquals($this->pageFolderRedirect->statusCode, $redirectResponse->statusCode);
    }
}
