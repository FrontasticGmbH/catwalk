<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

abstract class ReferenceValue extends DataObject
{
    public string $type;

    public bool $openInNewWindow = false;
}
