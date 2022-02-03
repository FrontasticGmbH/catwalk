<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Determines what type of dynamic page is matched and delivers the payload of the __master data source.
 *
 *
 *
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
     * Payload for the main (__master) data source of the dynamic page.
     *
     * The content of this field must be JSON serializable (e.g. does not have cyclic references).
     *
     * @required
     * @var mixed
     */
    public $dataSourcePayload;

    /**
     * Submit a payload Frontastic uses for scheduled page criterion matching (FECL)
     *
     * The content of this field must be JSON serializable (e.g. does not have cyclic references).
     *
     * @var mixed
     */
    public object $pageMatchingPayload;
}
