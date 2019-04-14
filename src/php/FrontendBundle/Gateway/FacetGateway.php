<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\FrontendBundle\Domain\Facet;

class FacetGateway
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

    public function get(string $facetId): Facet
    {
        if (($facet = $this->repository->findOneByFacetId($facetId)) === null) {
            throw new \OutOfBoundsException("Facet with ID $facetId could not be found.");
        }

        return $facet;
    }

    /**
     * @return Facet[]
     */
    public function getEnabled(): array
    {
        return $this->repository->findBy(['isEnabled' => true]);
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(f.sequence)
                FROM
                    Frontastic\\Catwalk\\FrontendBundle\\Domain\\Facet f"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(Facet $facet): Facet
    {
        $this->manager->persist($facet);
        $this->manager->flush();

        return $facet;
    }

    public function remove(Facet $facet)
    {
        $facet->isDeleted = true;
        $this->store($facet);
    }

    public function getEvenIfDeleted(string $facetId): Facet
    {
        return $this->withoutUndeletedFilter(function () use ($facetId) {
            return $this->get($facetId);
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
