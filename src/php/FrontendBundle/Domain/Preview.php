<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class Preview extends DataObject
{
    /**
     * @var string
     */
    public $previewId;

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var Node
     */
    public $node;

    /**
     * @var Page
     */
    public $page;

    /**
     * @var \FrontendBundle\UserBundle\Domain\MetaData
     */
    public $metaData;
}
