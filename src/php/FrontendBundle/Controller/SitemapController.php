<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\FrontendBundle\Domain\SitemapService;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapController
{
    private SitemapService $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function deliverAction(Request $request): Response
    {
        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);

        $sitemap = null;
        try {
            $sitemap = $this->sitemapService->loadLatestByPath($request->getPathInfo());
        } catch (\Throwable $e) {
            return new Response('Loading sitemap errored', 500);
        }

        if ($sitemap === null) {
            return new Response('Requested sitemap not found', 404);
        }

        return Response::create($sitemap->content, 200, ['Content-Type', 'application/xml'])
            ->setLastModified(new \DateTimeImmutable(
                '@' . $sitemap->generationTimestamp,
                new \DateTimeZone('UTC')
            ));
    }
}
