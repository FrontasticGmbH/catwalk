<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Preview;
use Frontastic\Catwalk\FrontendBundle\Domain\PreviewService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewData;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageRedirectResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DynamicPageSuccessResult;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\PageDataResponse;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\PagePreviewDataResponse;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend\RedirectResponse;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Page as NextjsPage;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\DynamicPageService;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\RedirectService;
use Frontastic\Catwalk\NextJsBundle\Domain\PageDataCompletionService;
use Frontastic\Catwalk\NextJsBundle\Domain\PageViewData as NextjsPageViewData;
use Frontastic\Catwalk\NextJsBundle\Domain\SiteBuilderPageService;
use Frontastic\Common\ReplicatorBundle\Domain\Customer;
use Frontastic\Common\ReplicatorBundle\Domain\Project;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageControllerTest extends TestCase
{
    private PageController $pageController;

    /**
     * @var SiteBuilderPageService|\Phake_IMock
     */
    private $siteBuilderPageServiceMock;
    /**
     * @var DynamicPageService|\Phake_IMock
     */
    private $dynamicPageService;
    /**
     * @var NodeService|\Phake_IMock
     */
    private $nodeServiceMock;
    /**
     * @var PageService|\Phake_IMock
     */
    private $pageServiceMock;
    /**
     * @var PreviewService|\Phake_IMock
     */
    private $previewServiceMock;
    /**
     * @var PageDataCompletionService|\Phake_IMock
     */
    private $completionServiceMock;
    /**
     * @var FromFrontasticReactMapper|\Phake_IMock
     */
    private $mapperMock;
    /**
     * @var ViewDataProvider|\Phake_IMock
     */
    private $viewDataProviderMock;
    /**
     * @var RedirectService|\Phake_IMock
     */
    private $redirectServiceMock;

    /**
     * @var Context
     */
    private $contextFixture;

    public function setUp()
    {
        $this->siteBuilderPageServiceMock = \Phake::mock(SiteBuilderPageService::class);
        $this->dynamicPageService = \Phake::mock(DynamicPageService::class);
        $this->nodeServiceMock = \Phake::mock(NodeService::class);
        $this->pageServiceMock = \Phake::mock(PageService::class);
        $this->previewServiceMock = \Phake::mock(PreviewService::class);
        $this->completionServiceMock = \Phake::mock(PageDataCompletionService::class);
        $this->mapperMock = \Phake::mock(FromFrontasticReactMapper::class);
        $this->viewDataProviderMock = \Phake::mock(ViewDataProvider::class);
        $this->redirectServiceMock = \Phake::mock(RedirectService::class);

        $this->contextFixture = new Context([
            'project' => new Project([
                'languages' => ['en_US'],
                'defaultLanguage' => 'en_US',
            ]),
            'customer' => new Customer([
                'name' => 'demo'
            ])
        ]);

        \Phake::when($this->viewDataProviderMock)->fetchDataFor->thenReturn(new ViewData([
            'stream' => new \stdClass(),
            'tastic' => new \stdClass(),
        ]));
        \Phake::when($this->mapperMock)->map->thenReturnCallback(function ($input) {
            if ($input instanceof ViewData) {
                return \Phake::mock(NextjsPageViewData::class);
            } else if ($input instanceof Node) {
                return \Phake::mock(PageFolder::class);
            } else if ($input instanceof Page) {
                return \Phake::mock(NextjsPage::class);
            }
            return $input;
        });
        \Phake::when($this->redirectServiceMock)->getRedirectResponseForPath->thenReturn(null);

        $this->pageController = new PageController(
            $this->siteBuilderPageServiceMock,
            $this->dynamicPageService,
            $this->mapperMock,
            $this->nodeServiceMock,
            $this->pageServiceMock,
            $this->previewServiceMock,
            $this->completionServiceMock,
            $this->viewDataProviderMock,
            $this->redirectServiceMock
        );
    }

    private function getFakeNode(array $data = [])
    {
        $nodeData = array_merge(
            [
                'nodeId' => 'fakeNodeId1',
                'path' => '/fake-node',
                'isMaster' => false,
                'nodeType' => 'landingpage',
                'sequence' => '1234',
                'streams' => [],
                'name' => 'Fake node',
                'depth' => 0,
                'sort' => 0,
                'children' => [],
                'metaData' => null,
                'error' => null,
                'isDeleted' => false
            ],
            $data
        );

        return new Node($nodeData);
    }

    private function getFakePage(array $data = [])
    {
        $pageData = array_merge([
            'pageId' => '1',
            'regions' => [],
            'state' => 'foo'
        ], $data);

        return new Page($pageData);
    }

    private function setupFakeRedirectForPath(string $path, array $queryParams)
    {
        \Phake::when($this->redirectServiceMock)
            ->getRedirectResponseForPath($path, $queryParams, $this->contextFixture)
            ->thenReturn(new RedirectResponse([
                'statusCode' => 301,
                'reason' => RedirectResponse::REASON_REDIRECT_EXISTS_FOR_PATH,
                'targetType' => RedirectResponse::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.cloud'
            ]));
    }

    public function validRequestProvider()
    {
        $path = '/redirect';
        $locale = 'en_US';

        $request = new Request();
        $request->headers->set('Frontastic-Path', $path);
        $request->headers->set('Frontastic-Locale', $locale);

        return [
            [$path, $locale, $request],
            [$path, $locale, new Request(['path' => $path, 'locale' => $locale])]
        ];
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testDynamicPageHandlingTriggeredWhenNoNodeFound($path, $locale, $request)
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn(new DynamicPageSuccessResult([
            'dynamicPageType' => 'test'
        ]));
        \Phake::when($this->dynamicPageService)->matchNodeFor->thenReturn($this->getFakeNode());
        \Phake::when($this->pageServiceMock)->fetchForNode->thenReturn($this->getFakePage());

        $responseData = $this->pageController->indexAction(
            $request,
            $this->contextFixture
        );

        \Phake::verify($this->dynamicPageService)->handleDynamicPage;
        \Phake::verify($this->dynamicPageService)->matchNodeFor;

        $this->assertInstanceOf(PageDataResponse::class, $responseData);
        $this->assertInstanceOf(PageFolder::class, $responseData->pageFolder);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testRedirectResponseSentWhenRedirectExistsForPath($path, $locale, $request)
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn(null);

        $this->setupFakeRedirectForPath($path, $request->query->all());

        $response = $this->pageController->indexAction($request, $this->contextFixture);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testRedirectResponseIsNotSentWhenSiteBuilderPageExists($path, $locale, $request)
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn('fakeNodeId1');
        \Phake::when($this->nodeServiceMock)->get('fakeNodeId1')->thenReturn($this->getFakeNode());
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn(null);

        $this->setupFakeRedirectForPath($path, $request->query->all());

        $response = $this->pageController->indexAction($request, $this->contextFixture);

        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testRedirectResponseIsNotSentWhenDynamicPageExists($path, $locale, $request)
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn(new DynamicPageSuccessResult([
            'dynamicPageType' => 'test'
        ]));
        \Phake::when($this->dynamicPageService)->matchNodeFor->thenReturn($this->getFakeNode());

        $this->setupFakeRedirectForPath($path, $request->query->all());

        $response = $this->pageController->indexAction($request, $this->contextFixture);

        $this->assertNotInstanceOf(RedirectResponse::class, $response);
    }

    public function testPreviewActionWithCorrectData()
    {
        $previewId = '1';

        \Phake::when($this->previewServiceMock)->get($previewId)->thenReturn(new Preview([
            'previewId' => $previewId,
            'createdAt' => new \DateTime(),
            'node' => $this->getFakeNode(),
            'page' => $this->getFakePage()
        ]));

        $request = new Request([
            'previewId' => $previewId,
            'locale' => 'en_US'
        ]);

        $response = $this->pageController->previewAction($request, $this->contextFixture);

        $this->assertInstanceOf(PagePreviewDataResponse::class, $response);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testDynamicPageRedirect($path, $locale, $request)
    {
        $result = new DynamicPageRedirectResult([
            'statusCode' => 301,
            'redirectLocation' => '/redirect-to'
        ]);

        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn($result);
        \Phake::when($this->redirectServiceMock)
            ->createResponseFromDynamicPageRedirectResult($result)
            ->thenReturn(new RedirectResponse([
                'statusCode' => $result->statusCode,
                'reason' => RedirectResponse::REASON_DYNAMIC_PAGE_REDIRECT,
                'targetType' => RedirectResponse::TARGET_TYPE_UNKNOWN,
                'target' => $result->redirectLocation
            ]));

        $response = $this->pageController->indexAction($request, $this->contextFixture);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals($result->statusCode, $response->statusCode);
        $this->assertEquals(RedirectResponse::REASON_DYNAMIC_PAGE_REDIRECT, $response->reason);
        $this->assertEquals(RedirectResponse::TARGET_TYPE_UNKNOWN, $response->targetType);
        $this->assertEquals($result->redirectLocation, $response->target);
    }

    public function testIndexActionMissingPath()
    {
        $request = new Request();
        $request->headers->set('Frontastic-Locale', 'en_US');

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Missing path');

        $this->pageController->indexAction($request, $this->contextFixture);
    }

    public function testIndexActionMissingLocale()
    {
        $request = new Request();
        $request->headers->set('Frontastic-Path', '/redirect');

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Missing locale');

        $this->pageController->indexAction($request, $this->contextFixture);
    }

    public function testIndexActionLocaleNotSupported()
    {
        $request = new Request();
        $request->headers->set('Frontastic-Path', '/redirect');
        $request->headers->set('Frontastic-Locale', 'pt_PT');

        $this->expectException(BadRequestHttpException::class);
        $this->expectExceptionMessage('Locale pt_PT not supported by project (en_US)');

        $this->pageController->indexAction($request, $this->contextFixture);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testIndexActionNodeAndRedirectDoesntExist($path, $locale, $request)
    {
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(null);
        \Phake::when($this->dynamicPageService)->handleDynamicPage->thenReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Could not resolve page from path');

        $this->pageController->indexAction($request, $this->contextFixture);
    }

    /**
     * @dataProvider validRequestProvider
     */
    public function testSuccessfulRequest($path, $locale, $request)
    {
        $nodeMock = \Phake::mock(Node::class);
        \Phake::when($this->siteBuilderPageServiceMock)->matchSiteBuilderPage->thenReturn(1);
        \Phake::when($this->nodeServiceMock)->get->thenReturn($nodeMock);
        $pageMock = \Phake::mock(Page::class);
        \Phake::when($this->pageServiceMock)->fetchForNode->thenReturn($pageMock);
        \Phake::when($this->pageServiceMock)->filterOutHiddenData->thenReturn($pageMock);

        $viewData = new ViewData([
            'stream' => new \stdClass(),
            'tastic' => new \stdClass(),
        ], false);
        \Phake::when($this->viewDataProviderMock)->fetchDataFor->thenReturn($viewData);
        $pageFolderMock = \Phake::mock(PageFolder::class);
        \Phake::when($this->mapperMock)->map($nodeMock)->thenReturn($pageFolderMock);
        $nextJsPageMock = \Phake::mock(NextjsPage::class);
        \Phake::when($this->mapperMock)->map($pageMock)->thenReturn($nextJsPageMock);
        \Phake::when($this->mapperMock)->map($viewData)->thenReturn($viewData);


        $result = $this->pageController->indexAction($request, $this->contextFixture);

        \Phake::verify($this->mapperMock)->map($nodeMock);
        \Phake::verify($this->mapperMock)->map($pageMock);
        \Phake::verify($this->mapperMock)->map($viewData);

        $this->assertNotNull($result);
        $this->assertEquals($pageFolderMock, $result->pageFolder);
        $this->assertEquals($nextJsPageMock, $result->page);
        $this->assertEquals($viewData, $result->data);
    }
}
