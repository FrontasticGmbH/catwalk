<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\FrontendBundle\Domain\Tastic as OriginalTastic;

use Kore\DataObject\DataObject;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each
 * of these fields and we only submit those which have been requested.
 * @type
 */
class DataSourceContext extends DataObject
{
    /**
     * @var Context
     */
    public ?Context $frontasticContext = null;

    /**
     * @var PageFolder
     */
    public ?PageFolder $pageFolder = null;

    /**
     * @var Page
     */
    public ?Page $page = null;

    /**
     * @var OriginalTastic[]|null
     */
    public ?array $usingTastics = null;

    /**
     * @var Request
     */
    public ?Request $request = null;
}
