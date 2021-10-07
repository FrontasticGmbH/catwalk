<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Sitemap extends DataObject
{
    public ?int $sitemapId;

    public int $generationTimestamp;

    public string $basedir;

    public string $filename;

    /**
     * This is $basedir + / + $filename for controller matching
     */
    public string $filepath;

    public string $content;
}
