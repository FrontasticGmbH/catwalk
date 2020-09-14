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
     * @required
     */
    public $schemaId;

    /**
     * @var string
     * @required
     */
    public $schemaType;

    /**
     * @var array
     * @required
     */
    public $schema;

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

    public function getSchemaConfiguration(): array
    {
        $schemaConfiguration = $this->schema['schema'] ?? [];
        if (!is_array($schemaConfiguration)) {
            $schemaConfiguration = [];
        }
        return $schemaConfiguration;
    }
}
