<?php

namespace Frontastic\Catwalk\NextJsBundle\Controller;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\ExtensionService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Exception\ExtensionRunnerException;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceContext;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceConfiguration;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\DataSourceResult;
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


        try {
            $dataSourceResultResponse = $this->extensionService->callDataSource(
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
                        'request' => $this->requestService->createApiRequest($request),
                        'isPreview' => boolval($request->get('__isPreview'))
                    ])
                ],
                null
            )->wait();

            $jsonPayload = json_decode(
                $dataSourceResultResponse,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (array_key_exists('ok', $jsonPayload) && $jsonPayload['ok'] == false) {
                $msg = isset($jsonPayload['message'])
                    ? $jsonPayload['message']
                    : "An unknown error occured in the extension runner. Raw response: " . $dataSourceResultResponse;
                throw new \Exception($msg);
            }
            return new DataSourceResult(
                $jsonPayload,
                true
            );
        } catch (ExtensionRunnerException $exception) {
            throw new \Exception(
                $exception->getMessage() . ' Context: ' . var_export($exception->getContext(), true),
                $exception->getCode(),
                $exception
            );
        }
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
