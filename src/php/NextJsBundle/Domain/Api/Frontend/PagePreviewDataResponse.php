<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

/**
 * Page data response returned by PageController@previewAction
 */
class PagePreviewDataResponse extends PageDataResponse
{
    /**
     * @var string
     */
    public $previewId;
}
