<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteRouters;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksService;
use Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter\ContentRouter as FrontasticContentRouter;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi\Content;
use Frontastic\Common\ContentApiBundle\Domain\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ContentRouter extends FrontasticContentRouter
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var HooksService
     */
    private $hooksService;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ContentApi
     */
    private $contentApi;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->container = $container;
    }

    public function generateUrlFor(Content $content): string
    {
        $params = $this->getHooksService()->callExpectArray(
            HooksCallBuilder::MASTER_PAGE_GENERATE_URL_FOR_CONTENT_ROUTER,
            [
                $content
            ]
        );
        if (empty($params)) {
            return parent::generateUrlFor($content);
        }

        return $this->getRouter()->generate('Frontastic.Frontend.Master.Content.view', $params);
    }

    public function identifyFrom(Request $request, Context $context): ?string
    {
        $attributes = $request->attributes->all();

        if (key_exists('id', $attributes)) {
            return $attributes['id'];
        }

        $contentQuery = $this->getHooksService()->callExpectObject(
            HooksCallBuilder::MASTER_PAGE_IDENTIFY_FROM_CONTENT_ROUTER,
            [
                new Query(),
                $attributes,
                $context
            ]
        );

        if (empty($contentQuery)) {
            return parent::identifyFrom($request, $context);
        }

        $result = $this->getContentApi()->query($contentQuery, $context->locale);

        if (empty($result->items)) {
            return null;
        }
        return $result->items[0]->contentId;
    }

    private function getHooksService(): HooksService
    {
        if (null === $this->hooksService) {
            $this->hooksService = $this->container->get(HooksService::class);
        }
        return $this->hooksService;
    }

    private function getContentApi(): ContentApi
    {
        if (null === $this->contentApi) {
            $this->contentApi = $this->container->get('frontastic.catwalk.content_api');
        }
        return $this->contentApi;
    }

    private function getRouter(): Router
    {
        if (null === $this->router) {
            $this->router = $this->container->get('router');
        }
        return $this->router;
    }
}
