<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\TasticFieldValue;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class PageFolderValue extends DataObject
{
    /**
     * @var string
     */
    public string $pageFolderId;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var object
     */
    public object $configuration;

    // phpcs:disable
    /**
     * @var array
     */
    public array $_urls;
    // phpcs:enable
}
