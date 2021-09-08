<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextDecorator;
use Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;

class SchemaService implements Target, ContextDecorator
{
    /**
     * @var SchemaGateway
     */
    private $schemaGateway;

    public function __construct(SchemaGateway $schemaGateway)
    {
        $this->schemaGateway = $schemaGateway;
    }

    public function lastUpdate(): string
    {
        return $this->schemaGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $schema = $this->schemaGateway->getEvenIfDeleted($update['schemaId']);
            } catch (\OutOfBoundsException $exception) {
                $schema = new Schema([
                    'schemaId' => $update['schemaId'],
                ]);
            }

            $schema = $this->fill($schema, $update);
            $this->schemaGateway->store($schema);
        }
    }

    public function decorate(Context $context): Context
    {
        $projectConfigurationSchema = $this->schemaGateway->getSchemaOfType(Schema::TYPE_PROJECT_CONFIGURATION);
        if ($projectConfigurationSchema !== null) {
            $context->projectConfigurationSchema = $projectConfigurationSchema->getSchemaConfiguration();
        }
        return $context;
    }

    public function completeNodeData(Node $node, ?FieldVisitor $fieldVisitor = null): void
    {
        // TODO: Cache!
        $nodeSchema = $this->schemaGateway->getSchemaOfType(Schema::TYPE_NODE_CONFIGURATION);
        if ($nodeSchema === null) {
            return;
        }

        $configuration = ConfigurationSchema::fromSchemaAndConfiguration(
            $schema = $nodeSchema->schema['schema'] ?? [],
            $node->configuration
        );

        $node->configuration = $configuration->getCompleteValues($fieldVisitor);
    }

    private function fill(Schema $schema, array $data): Schema
    {
        $schema->schemaType = $data['schemaType'];
        $schema->schema = $data['schema'];
        $schema->sequence = $data['sequence'];
        $schema->metaData = $data['metaData'];
        $schema->isDeleted = (bool)$data['isDeleted'];

        return $schema;
    }
}
