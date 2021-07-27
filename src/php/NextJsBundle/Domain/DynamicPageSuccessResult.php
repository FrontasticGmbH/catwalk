<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class DynamicPageSuccessResult extends DataObject implements DynamicPageResult
{
    /**
     * Unique identifier for the page type matched. Will correlate with configuration in Frontastic studio.
     *
     * @var string
     */
    public string $dynamicPageType;

    /**
     * Payload for the main data source of the dynamic page.
     *
     * @var mixed JSON serializable
     */
    public $dataSourcePayload;

    /**
     * Submit a payload we use for page matching (FECL!)
     *
     * @var object
     */
    public object $pageMatchingPayload;
}
