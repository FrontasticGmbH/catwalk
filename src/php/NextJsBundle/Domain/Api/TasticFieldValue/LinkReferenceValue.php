<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

class LinkReferenceValue extends ReferenceValue
{
    public string $type = 'link';

    public string $link;

    public string $target;
}
