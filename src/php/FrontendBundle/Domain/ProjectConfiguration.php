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
     * @required
     */
    public $projectConfigurationId;

    /**
     * array
     * @required
     */
    public $configuration;

    /**
     * @var \Frontastic\Backstage\UserBundle\Domain\MetaData
     * @required
     */
    public $metaData;

    /**
     * @var string
     * @required
     */
    public $sequence;

    /**
     * @var bool
     * @required
     */
    public $isDeleted = false;
}
