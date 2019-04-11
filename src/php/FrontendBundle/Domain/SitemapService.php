<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

class SitemapService
{
    /**
     * @var \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[]
     */
    private $sitemapExtensions;

    /**
     * @param \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[] $sitemapExtensions
     */
    public function __construct($sitemapExtensions)
    {
        $this->sitemapExtensions = $sitemapExtensions;
    }

    /**
     * @return \Frontastic\Catwalk\FrontendBundle\Domain\SitemapExtension[]
     */
    public function getExtensions(): iterable
    {
        return $this->sitemapExtensions;
    }
}
