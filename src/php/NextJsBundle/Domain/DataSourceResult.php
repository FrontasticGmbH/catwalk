<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class DataSourceResult extends DataObject
{
    /**
     * Arbitrary payload information returned by the data source.
     *
     * Note: We might not de-serialize this in API hub.
     *
     * @var mixed any JSON serializable value
     */
    public object $dataSourcePayload;
}
