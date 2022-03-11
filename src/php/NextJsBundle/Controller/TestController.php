<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceConfiguration;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\Page;
use Frontastic\Catwalk\NextJsBundle\Domain\FromFrontasticReactMapper;
use Frontastic\Catwalk\NextJsBundle\Domain\RequestService;

class TestController
{
    private ExtensionService $extensionService;
    private RequestService $requestService;
    private FromFrontasticReactMapper $mapper;

    public function __construct(
        ExtensionService $extensionService,
        RequestService $requestService,
        FromFrontasticReactMapper $mapper
    ) {
        $this->extensionService = $extensionService;
        $this->requestService = $requestService;
        $this->mapper = $mapper;
    }

    public function dataSourceAction(string $identifier, SymfonyRequest $request, Context $context)
    {
        if ($request->headers->get('Content-Type') != 'application/json') {
            return $this->unsupportedContentType();
        }

        $requestContentJson = json_decode($request->getContent(), true);

        // copied from Typescript TODO: put that in a central place
        $hookName = 'data-source-' . str_replace('/', '-', $identifier);

        return $this->extensionService->call(
            $hookName,
            [
                new DataSourceConfiguration([
                    'configuration' => $requestContentJson
                ]),
                new DataSourceContext([
                    'frontasticContext' => $this->mapper->map($context),
                    'pageFolder' => new PageFolder(
                        ['pageFolderId' => 'somePageFolderId']
                    ),
                    'page' => new Page(
                        ['pageId' => 'somePageId', 'state' => 'development']
                    ),
                    'usingTastics' => null,
                    'request' => $this->requestService->createApiRequest($request)
                ])
            ]
        );
    }

    private function unsupportedContentType(): SymfonyResponse
    {
        $response = new SymfonyResponse();
        $response->setContent('Only JSON is supported (set Content-Type to \'application/json\')');
        $response->setStatusCode(SymfonyResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $response->headers->set('Content-Type', 'text/plain');
        return $response;
    }
}
