<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\Api;

use Kore\DataObject\DataObject;

class PageFolderStructureValue extends DataObject
{
    /**
     * @replaces $nodeId.
     * @required
     * @var string
     */
    public string $pageFolderId;

    /**
     * @replaces $nodeType
     * @required
     * @var string
     */
    public string $pageFolderType = 'landingpage';

    /**
     * @required
     * @var array
     */
    public array $configuration = [];

    /**
     * @var string
     */
    public string $name;

    /**
     * Materialized path of IDs of ancestor page folders.
     *
     * @replaces $path
     * @required
     * @var string
     */
    public string $ancestorIdsMaterializedPath;

    /**
     * Depth of this page folder in the page folder tree.
     *
     * @var integer
     * @required
     */
    public $depth;

    /**
     * Sort order in the page folder tree.
     *
     * @var integer
     * @required
     */
    public $sort = 0;

    /**
     * @var PageFolderBreadcrumb[]
     */
    public array $breadcrumbs = [];

    // phpcs:disable
    /**
     * @var array
     */
    public array $_urls;

    /**
     * The url for the current locale
     * @var string
     */
    public string $_url;
    // phpcs:enable
}
