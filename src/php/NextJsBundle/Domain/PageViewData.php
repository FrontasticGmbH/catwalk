<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

/**
 * @replaces \Frontastic\Catwalk\FrontendBundle\Domain\ViewData
 */
class PageViewData
{
    /**
     * Hashmap indexed by data source ID assigned to corresponding (arbitrary) data
     *
     * @replaces $stream
     * @var object
     */
    public object $dataSources = (object)[];

    /**
     * @removed We can re-add "tastic field handlers" if those are actually of use for developers
     */
    // public $tastic;
}
