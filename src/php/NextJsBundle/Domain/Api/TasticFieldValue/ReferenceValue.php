<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

/**
 * This type is not exported to TypeScript. We need to define it manually as the
 * data structure given to components differs from the structure of this class.
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
