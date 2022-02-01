<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\Page;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\PageViewData;
use Kore\DataObject\DataObject;

/**
 * Page data response returned by PageController@indexAction
 */
class PageDataResponse extends DataObject
{
    /**
     * @var Page
     */
    public Page $page;

    /**
     * @var PageFolder
     */
    public PageFolder $pageFolder;

    /**
     * @var PageViewData|\stdClass
     */
    public $data;
}
