<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

class DataSourceReference extends DataObject
{
    public string $dataSourceType;

    public ?string $dataSourceId = null;
}
