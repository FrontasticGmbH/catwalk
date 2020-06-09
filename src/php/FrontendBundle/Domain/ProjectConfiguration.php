<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class ProjectConfiguration extends DataObject
{
    /**
     * @var string
     */
    public $projectConfigurationId;

    /**
     * array
     */
    public $configuration;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
