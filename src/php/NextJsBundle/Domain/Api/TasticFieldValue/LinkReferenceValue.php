<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

/**
 * @type
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
    public ?string $link;

    /**
     * @var string
     */
    public string $target;
}
