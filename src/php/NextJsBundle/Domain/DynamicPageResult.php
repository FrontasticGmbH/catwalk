<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class DynamicPageResult extends DataObject
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
     * @var object
     */
    public object $dataSourcePayload;

    /**
     * Optional payload that is used for matching logic of this dynamic page.
     *
     * This allows to transmit a smaller payload for matching which will safe execution time (compared to decoding the full payload already).
     *
     * @fixme: Is that a premature optimization?
     * @var object|null
     */
    public ?object $pageMatchingPayload = null;
}
