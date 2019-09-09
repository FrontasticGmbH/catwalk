<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface SitemapExtension
{
    public function getName(): string;

    /**
     * Returns an array of arrays with the following structure:
     *   [
     *     'uri' => string,
     *     'images' => string[], // optional
     *   ]
     * @return array
     */
    public function getEntries(): array;
}
