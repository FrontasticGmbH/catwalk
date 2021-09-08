<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

class PageFolderValue extends DataObject
{
    public string $pageFolderId;

    public string $name;

    public object $configuration;

    // phpcs:disable
    public array $_urls;
    // phpcs:enable
}
