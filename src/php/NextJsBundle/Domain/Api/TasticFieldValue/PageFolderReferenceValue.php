<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

class PageFolderReferenceValue extends ReferenceValue
{
    public string $type = 'page-folder';

    public PageFolderValue $pageFolder;
}
