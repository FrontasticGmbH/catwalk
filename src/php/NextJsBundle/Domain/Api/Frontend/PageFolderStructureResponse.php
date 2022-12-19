<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderStructureValue;
use Kore\DataObject\DataObject;

/**
 * Tree data response returned by tree endpoint
 */
class PageFolderStructureResponse extends DataObject
{
    /**
     * @var PageFolderStructureValue[]
     * @required
     */
    public array $pageFolderStructure;
}
