<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

/**
 * Page preview data response returned by page preview endpoint
 */
class PagePreviewDataResponse extends PageDataResponse
{
    /**
     * @var string
     * @required
     */
    public $previewId;

    /**
     * @var PagePreviewContext
     * @required
     */
    public $previewContext;
}
