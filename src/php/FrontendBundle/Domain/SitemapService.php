<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\SitemapGateway;

class SitemapService
{
    /**
     * @var \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[]
     */
    private $sitemapExtensions;
    private SitemapGateway $sitemapGateway;

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[] $sitemapExtensions
     */
    public function __construct(SitemapGateway $sitemapGateway, $sitemapExtensions)
    {
        $this->sitemapGateway = $sitemapGateway;
        $this->sitemapExtensions = $sitemapExtensions;
    }

    /**
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[]
     */
    public function getExtensions(): iterable
    {
        return $this->sitemapExtensions;
    }

    public function storeAll(array $sitemaps)
    {
        $this->sitemapGateway->storeAll($sitemaps);
    }

    public function loadLatestByPath(string $path): ?Sitemap
    {
        return $this->sitemapGateway->loadLatestByPath($path);
    }
}
