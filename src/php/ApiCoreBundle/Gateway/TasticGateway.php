<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Tastic;

class TasticGateway
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

    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function get(string $tasticId): Tastic
    {
        if (($tastic = $this->repository->findOneByTasticId($tasticId)) === null) {
            throw new \OutOfBoundsException("Tastic with ID $tasticId could not be found.");
        }

        return $tastic;
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(w.sequence)
                FROM
                    Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\Tastic w"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(Tastic $tastic): Tastic
    {
        $this->manager->persist($tastic);
        $this->manager->flush();

        return $tastic;
    }

    public function remove(Tastic $tastic)
    {
        $tastic->isDeleted = true;
        $this->store($tastic);
    }

    public function getEvenIfDeleted(string $tasticId): Tastic
    {
        return $this->withoutUndeletedFilter(function () use ($tasticId) {
            return $this->get($tasticId);
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
