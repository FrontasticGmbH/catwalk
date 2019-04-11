<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface SitemapExtension
{
    public function getName(): string;

    public function getUrls(): array;
}
