<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextDecorator;
use Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;
use Frontastic\Common\SpecificationBundle\Domain\ConfigurationSchema;
use Frontastic\Common\SpecificationBundle\Domain\Schema\FieldVisitor;
use Psr\SimpleCache\CacheInterface;

class SchemaService implements Target, ContextDecorator
{
    private const SCHEMA_CACHE_KEY = 'frontastic.schema';

    /**
     * @var Context
     */
    private $context;

    /**
     * @var SchemaGateway
     */
    private $schemaGateway;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $cache;

    public function __construct(
        Context $context,
        SchemaGateway $schemaGateway,
        CacheInterface $cache
    )
    {
        $this->context = $context;
        $this->schemaGateway = $schemaGateway;
        $this->cache = $cache;
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
        $nodeSchema = $this->getNodeSchema();

        if ($nodeSchema === null) {
            return;
        }

        $configuration = ConfigurationSchema::fromSchemaAndConfiguration(
            $schema = $nodeSchema->schema['schema'] ?? [],
            $node->configuration
        );

        $node->configuration = $configuration->getCompleteValues($fieldVisitor);
    }

    private function getNodeSchema(): ?Schema
    {
        $nodeSchema = $this->cache->get($this->projectSchemaCacheKey(), false);

        if ($nodeSchema === false) {
            $nodeSchema = $this->schemaGateway->getSchemaOfType(Schema::TYPE_NODE_CONFIGURATION);
            $this->cache->set($this->projectSchemaCacheKey(), $nodeSchema, 600);
        }

        return $nodeSchema;
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

    private function projectSchemaCacheKey() {
        return $this->context->project->name. '-' . self::SCHEMA_CACHE_KEY;
    }
}
