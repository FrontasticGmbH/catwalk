<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\Page;
use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolder;
use Frontastic\Catwalk\NextJsBundle\Domain\PageViewData;
use Kore\DataObject\DataObject;

/**
 * Page data response returned by page endpoint
 */
class PageDataResponse extends DataObject
{
    /**
     * @var Page
     * @required
     */
    public Page $page;

    /**
     * @var PageFolder
     * @required
     */
    public PageFolder $pageFolder;

    /**
     * @var PageViewData|object
     * @required
     */
    public $data;
}
