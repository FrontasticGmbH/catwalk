<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Preview extends DataObject
{
    /**
     * @var string
     * @required
     */
    public $previewId;

    /**
     * @var \DateTime
     * @required
     */
    public $createdAt;

    /**
     * @var Node
     * @required
     */
    public $node;

    /**
     * @var Page
     * @required
     */
    public $page;

    /**
     * @var \FrontendBundle\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;
}
