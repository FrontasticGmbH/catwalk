<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Frontastic\Catwalk\FrontendBundle\Domain\Tastic as OriginalTastic;

use Kore\DataObject\DataObject;

/**
 * This class only represents the information available, the TypeScript API should provide a mechanism to request each
 * of these fields and we only submit those which have been requested.
 */
class DataSourceContext extends DataObject
{
    public ?Context $frontasticContext = null;

    public ?PageFolder $pageFolder = null;

    public ?Page $page = null;

    /**
     * @var OriginalTastic[]|null
     */
    public ?array $usingTastics = null;

    public ?Request $request = null;
}
