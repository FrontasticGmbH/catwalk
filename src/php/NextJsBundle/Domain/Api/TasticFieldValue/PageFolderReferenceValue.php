<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

/**
 * @type
 */
class PageFolderReferenceValue extends ReferenceValue
{
    /**
     * @var string
     */
    public string $type = 'page-folder';

    /**
     * @var PageFolderValue
     */
    public PageFolderValue $pageFolder;
}
