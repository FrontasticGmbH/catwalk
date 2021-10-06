<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Sitemap extends DataObject
{
    public ?int $sitemapId;

    public int $generationTimestamp;

    public string $filename;

    public string $content;
}
