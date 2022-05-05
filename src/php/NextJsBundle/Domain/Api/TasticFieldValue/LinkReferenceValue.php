<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

/**
 * This type is not exported to TypeScript. We need to define it manually as the
 * data structure given to components differs from the structure of this class.
 */
class LinkReferenceValue extends ReferenceValue
{
    /**
     * @var string
     */
    public string $type = 'link';

    /**
     * @var ?string
     */
    public ?string $link = null;

    /**
     * @var string
     */
    public string $target;
}
