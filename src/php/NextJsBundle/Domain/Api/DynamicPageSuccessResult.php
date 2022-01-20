<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class DynamicPageSuccessResult extends DataObject implements DynamicPageResult
{
    /**
     * Unique identifier for the page type matched. Will correlate with configuration in Frontastic studio.
     *
     * @required
     * @var string
     */
    public string $dynamicPageType;

    /**
     * Payload for the main data source of the dynamic page.
     *
     * JSON serializable
     *
     * @required
     * @var mixed
     */
    public $dataSourcePayload;

    /**
     * Submit a payload we use for page matching (FECL!)
     *
     * @var object
     */
    public object $pageMatchingPayload;
}
