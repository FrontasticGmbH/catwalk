<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Kore\DataObject\DataObject;

/**
 * @type
 */
class Schema extends DataObject
{
    public const TYPE_NODE_CONFIGURATION = 'nodeConfiguration';
    public const TYPE_CELL_CONFIGURATION = 'cellConfiguration';
    public const TYPE_PROJECT_CONFIGURATION = 'projectConfiguration';

    /**
     * @var string
     */
    public $schemaId;

    /**
     * @var string
     */
    public $schemaType;

    /**
     * @var array
     */
    public $schema;

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

    public function getSchemaConfiguration(): array
    {
        $schemaConfiguration = $this->schema['schema'] ?? [];
        if (!is_array($schemaConfiguration)) {
            $schemaConfiguration = [];
        }
        return $schemaConfiguration;
    }
}
