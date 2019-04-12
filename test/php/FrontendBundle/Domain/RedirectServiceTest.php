<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Router;

class RedirectServiceTest extends TestCase
{
    /**
     * @var RedirectService
     */
    private $redirectService;

    private $redirectGatewayMock;

    private $routerMock;

    protected function setUp()
    {
        $this->redirectGatewayMock = \Phake::mock(RedirectGateway::class);
        $this->routerMock = \Phake::mock(Router::class);

        $this->redirectService = new RedirectService(
            $this->redirectGatewayMock,
            $this->routerMock
        );
    }

    public function testGetRedirectForRequestWithNoRedirectsForPath()
    {
        $path = '/foo/bar/baz';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);

        $this->assertNull($url);
    }

    public function testGetRedirectForRequestWithoutParametersWithSingleMatchingLinkRedirect()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter = sprintf('%s?%s=1', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectWithQueryParametersInTargetUrl()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page?key=value&otherKey=nextValue';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter = sprintf('%s&%s=1', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectWithQueryParametersAndFragmentInTargetUrl()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page?key=value&otherKey=nextValue';
        $targetFragment = '#interesting-section';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target . $targetFragment,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter =
            sprintf('%s&%s=1%s', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY, $targetFragment);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectWithQueryParametersAndFragmentInTargetUrlAndWithAdditionalParameters()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page?key=value&otherKey=nextValue';
        $targetFragment = '#interesting-section';
        $queryParameters = new ParameterBag([
            'key1' => 'value1',
            'key2' => 'value2',
        ]);

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target . $targetFragment,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter =
            sprintf('%s&key1=value1&key2=value2&%s=1%s',
                $target,
                RedirectService::REDIRECT_COUNT_PARAMETER_KEY,
                $targetFragment);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectWithFragmentInTargetUrl()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page';
        $targetFragment = '#interesting-section';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target . $targetFragment,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter =
            sprintf('%s?%s=1%s', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY, $targetFragment);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectForRequestWithoutParametersWithSingleMatchingNodeRedirect()
    {
        $path = '/foo/bar/baz';
        $nodeId = '123';
        $nodeUrl = '/node/123';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_NODE,
                'target' => $nodeId,
            ]),
        ]);

        \Phake::when($this->routerMock)->generate('node_' . $nodeId)->thenReturn($nodeUrl);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $nodeUrlWithCounter = sprintf('%s?%s=1', $nodeUrl, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($nodeUrlWithCounter, $url);
    }

    public function testGetRedirectForRequestWithoutRedirectMatchingParameters()
    {
        $path = '/foo/bar/baz';
        $queryParameters = new ParameterBag([
            'otherParam' => '4711',
        ]);

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'query' => 'param1=test',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/nice-page',
            ]),
            new Redirect([
                'redirectId' => '2',
                'path' => $path,
                'query' => 'param2=17',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/other-page',
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);

        $this->assertNull($url);
    }

    public function testGetRedirectForRequestWithSingleRedirectMatchingParameters()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page';
        $queryParameters = new ParameterBag([
            'param1' => 'test',
            'arrayParam' => ['foo', 'bar', '17'],
            'otherParam' => '4711',
        ]);

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'query' => 'param1=test&arrayParam[]=foo&arrayParam[]=bar&arrayParam[]=17',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target,
            ]),
            new Redirect([
                'redirectId' => '2',
                'path' => $path,
                'query' => 'param2=17',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/other-page',
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter = sprintf('%s?otherParam=4711&%s=1', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectForRequestWithArrayParametersInWrongOrder()
    {
        $path = '/foo/bar/baz';
        $queryParameters = new ParameterBag([
            'param1' => 'test',
            'arrayParam' => ['bar', 'foo'],
            'otherParam' => '4711',
        ]);

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'path' => $path,
                'query' => 'param1=test&arrayParam[]=foo&arrayParam[]=bar',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/nice-page',
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);

        $this->assertNull($url);
    }

    public function testGetRedirectForRequestSortsMatchingRedirectsBySequence()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page';
        $queryParameters = new ParameterBag();

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'sequence' => '12',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/other-page',
            ]),
            new Redirect([
                'redirectId' => '2',
                'sequence' => '10',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target,
            ]),
            new Redirect([
                'redirectId' => '3',
                'sequence' => '11',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/third-page',
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter = sprintf('%s?%s=1', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($targetWithCounter, $url);
    }

    public function testGetRedirectForRequestSortsMatchingRedirectsByNumberOfParameters()
    {
        $path = '/foo/bar/baz';
        $target = 'https://frontastic.io/nice-page';
        $queryParameters = new ParameterBag([
            'param1' => 'foo',
            'param2' => 'bar',
            'param3' => 'baz',
        ]);

        \Phake::when($this->redirectGatewayMock)->getByPath($path)->thenReturn([
            new Redirect([
                'redirectId' => '1',
                'sequence' => '10',
                'path' => $path,
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/other-page',
            ]),
            new Redirect([
                'redirectId' => '2',
                'sequence' => '11',
                'path' => $path,
                'query' => 'param1=foo&param2=bar',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => 'https://frontastic.io/third-page',
            ]),
            new Redirect([
                'redirectId' => '3',
                'sequence' => '12',
                'path' => $path,
                'query' => 'param1=foo&param2=bar&param3=baz',
                'targetType' => Redirect::TARGET_TYPE_LINK,
                'target' => $target,
            ]),
        ]);

        $url = $this->redirectService->getRedirectUrlForRequest($path, $queryParameters);
        $targetWithCounter = sprintf('%s?%s=1', $target, RedirectService::REDIRECT_COUNT_PARAMETER_KEY);

        $this->assertEquals($targetWithCounter, $url);
    }
}
