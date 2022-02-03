<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

/**
 * Context retrieved by a "data-source" extension.
 *
 * All fields in the context are optional. We want to introduce a mechanism in the future that allows extensions to
 * annotate which context data they require.
 *
 * @type
 */
class DataSourceContext extends DataObject
{
    /**
     * @var Context
     */
    public ?Context $frontasticContext = null;

    /**
     * The page folder being rendered.
     *
     * @var PageFolder
     */
    public ?PageFolder $pageFolder = null;

    /**
     * The page being rendered.
     *
     * @var Page
     */
    public ?Page $page = null;

    /**
     * Tastics on the page which are using this data source.
     *
     * @var Tastic[]|null
     */
    public ?array $usingTastics = null;

    /**
     * @var Request
     */
    public ?Request $request = null;
}
