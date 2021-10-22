<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

/**
 * @type
 */
class PageFolderTreeValue extends PageFolderValue
{
    /**
     * @var PageFolderTreeValue[]
     */
    public array $children = [];

    /**
     * @var ?int
     */
    public ?int $requestedDepth = null;
}
