<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class DataSourceResult extends DataObject
{
    /**
     * Arbitrary payload information returned by the data source.
     *
     * @var object
     */
    public object $dataSourcePayload;
}
