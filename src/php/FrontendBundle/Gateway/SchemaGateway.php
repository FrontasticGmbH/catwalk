<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\Schema;

class SchemaGateway
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $manager;

    public function __construct(EntityRepository $repository, EntityManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function getSchemaOfType(string $schemaType): ?Schema
    {
        return $this->repository->findOneBySchemaType($schemaType);
    }

    public function get(string $schemaId): Schema
    {
        if (($schema = $this->repository->find($schemaId)) === null) {
            throw new \OutOfBoundsException("Schema with ID $schemaId could not be found.");
        }

        return $schema;
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(s.sequence)
                FROM
                    Frontastic\\Catwalk\\FrontendBundle\\Domain\\Schema s"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(Schema $schema): Schema
    {
        $this->manager->persist($schema);
        $this->manager->flush();

        return $schema;
    }

    public function getEvenIfDeleted(string $schemaId): Schema
    {
        return $this->withoutUndeletedFilter(function () use ($schemaId) {
            return $this->get($schemaId);
        });
    }

    private function withoutUndeletedFilter(callable $callback)
    {
        $this->manager->getFilters()->disable('undeleted');
        try {
            return $callback();
        } finally {
            $this->manager->getFilters()->enable('undeleted');
        }
    }
}
