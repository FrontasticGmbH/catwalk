<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

/**
 * @type
 */
abstract class ReferenceValue extends DataObject
{
    /**
     * @var string
     */
    public string $type;

    /**
     * @var bool
     */
    public bool $openInNewWindow = false;
}
