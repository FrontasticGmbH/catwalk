<?php

namespace Frontastic\Catwalk\FrontendBundle\Routing\ObjectRouter;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi\Content;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ContentRouter
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var ContentApi
     */
    private $contentApi;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        /*
         * IMPORTANT: We must use the container here, otherwise we try to load
         * the ContextService in context free situations.
         */
        $this->container = $container;
    }

    public function generateUrlFor(Content $content)
    {
        return $this->getRouter()->generate(
            'Frontastic.Frontend.Master.Content.view',
            [
                'url' => strtr($content->slug, [
                    '_' => '/'
                ]),
                'identifier' => $content->contentId,
            ]
        );
    }

    public function identifyFrom(Request $request, Context $context): ?string
    {
        $content = $this->getContentApi()->getContent(
            $request->attributes->get('identifier'),
            $context->locale
        );

        if (!$content) {
            return null;
        }
        return $content->contentId;
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
