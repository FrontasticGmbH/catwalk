<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;

/**
 * Stripped down version of {@link Node}
 */
class PageFolder
{
    /**
     * @required
     */
    public string $pageFolderId;

    /**
     * @required
     */
    public bool $isDynamic = false;

    /**
     * @required
     */
    public string $pageFolderType = 'landingpage';

    /**
     * @required
     */
    public string $sequence;

    /**
     * @required
     */
    public array $configuration = [];

    /**
     * @var DataSourceConfiguration[]
     * @required
     */
    public array $dataSourceConfigurations = [];

    public string $name;

    /**
     * @var string[]
     * @required
     */
    public array $path = [];

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
     * @var Node[]
     * @required
     * @fixme Do we want to transmit that?
     */
    // public $children = [];

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     * @required
     * @fixme IMO we should not send that
     */
    // public $metaData;

    /**
     * Not needed because deleted page folders will never be transmitted to the customer.
     *
     * @var bool
     */
    // public $isDeleted = false;
}
