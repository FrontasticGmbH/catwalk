<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

class CustomDataSource extends DataObject
{
    /**
     * @var string
     */
    public $customDataSourceId;

    /**
     * @var string
     */
    public $customDataSourceType;

    /**
     * @var string
     */
    public $sequence;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $category = 'General';

    /**
     * @var \stdClass|array
     */
    public $configurationSchema;

    /**
     * @var array<string, bool>
     */
    public $environments = [];

    /**
     * @var \Frontastic\UserBundle\Domain\MetaData
     */
    public $metaData;

    /**
     * @var bool
     */
    public $isActive = true;

    /**
     * @var bool
     */
    public $isDeleted = false;
}
