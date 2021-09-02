<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

class PageFolderTreeValue extends PageFolderValue
{
    /**
     * @var PageFolderTreeValue[]
     */
    public array $children = [];

    public ?int $requestedDepth = null;
}
