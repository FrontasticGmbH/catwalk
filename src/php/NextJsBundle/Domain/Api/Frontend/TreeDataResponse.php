<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Frontastic\Catwalk\NextJsBundle\Domain\Api\PageFolderTreeNode;
use Kore\DataObject\DataObject;

/**
 * Tree data response returned by tree endpoint
 */
class TreeDataResponse extends DataObject
{
    /**
     * @var PageFolderTreeNode[]
     * @required
     */
    public array $tree;
}
