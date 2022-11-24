<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Kore\DataObject\DataObject;

class PageFolderBreadcrumb extends DataObject
{
    /**
     * @required
     * @var string
     */
    public string $pageFolderId;

    /**
     * @var array
     */
    public array $pathTranslations;

    /**
     * @var string
     */
    public string $ancestorIdsMaterializedPath;
}
