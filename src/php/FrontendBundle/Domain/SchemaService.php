<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

class SchemaService implements Target
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
