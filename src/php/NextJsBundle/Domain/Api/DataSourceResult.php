<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class DataSourceResult extends DataObject
{
    /**
     * Arbitrary payload information returned by the data source.
     *
     * Note: We might not de-serialize this in API hub.
     *
     * dataSourcePayload any JSON serializable value
     *
     * @required
     * @var mixed
     */
    public $dataSourcePayload;
}
