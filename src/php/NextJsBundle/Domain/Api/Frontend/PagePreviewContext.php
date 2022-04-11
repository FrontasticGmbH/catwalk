<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api\Frontend;

use Kore\DataObject\DataObject;

/**
 * Page preview context
 */
class PagePreviewContext extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $customerName;
}
