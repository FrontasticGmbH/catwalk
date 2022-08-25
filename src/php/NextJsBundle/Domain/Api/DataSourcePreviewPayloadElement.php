<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class DataSourcePreviewPayloadElement extends DataObject
{
    /**
     * This will show up as the name of the element in the
     * data source preview list in Studio.
     * @var string
     * @required
     */
    public string $title;

    /**
     * This is the image URL that will be loaded when viewing
     * the data source preview list in Studio.
     * @var string
     */
    public ?string $image;
}
