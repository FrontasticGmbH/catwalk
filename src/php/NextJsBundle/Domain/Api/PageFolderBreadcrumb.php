<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
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
