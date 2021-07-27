<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

/**
 * @replaces Frontastic\Catwalk\FrontendBundle\Domain\Node
 */
class PageFolder
{
    /**
     * @replaces $nodeId.
     * @required
     */
    public string $pageFolderId;

    /**
     * @replaces $isMaster
     * @required
     */
    public bool $isDynamic = false;

    /**
     * @replaces $nodeType
     * @required
     */
    public string $pageFolderType = 'landingpage';

    /**
     * @removed Sequence is not meaningful to the customer and can be re-added later, if needed
     */
    // public string $sequence;

    /**
     * @required
     */
    public array $configuration = [];

    /**
     * @replaces $streams
     * @var DataSourceConfiguration[]
     * @required
     */
    public array $dataSourceConfigurations = [];

    public string $name;

    /**
     * @replaces $path
     * @var string[]
     * @required
     */
    public array $ancestorIdsMaterializedPath = [];

    /**
     * @var integer
     */
    public $depth;

    /**
     * @var integer
     * @required
     */
    public $sort = 0;

    /**
     * @removed Removing this for now as it is ambiguously used and can lead to large payloads.
     */
    // public $children = [];

    /**
     * @removed MetaData is not relevant to API hub but only studio
     */
    // public $metaData;

    /**
     * @removed extensions will never be called for deleted data
     */
    // public $isDeleted = false;
}
