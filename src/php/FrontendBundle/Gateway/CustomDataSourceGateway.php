<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Frontastic\Catwalk\FrontendBundle\Domain\CustomDataSource;

class CustomDataSourceGateway
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var EntityManager
     */
    protected $manager;

    public function __construct(EntityRepository $repository, EntityManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function get(string $customDataSourceId): CustomDataSource
    {
        if (($customDataSource = $this->repository->findOneByCustomDataSourceId($customDataSourceId)) === null) {
            throw new \OutOfBoundsException("Custom Data Source with ID $customDataSourceId could not be found.");
        }

        return $customDataSource;
    }

    public function getEvenIfDeleted(string $customDataSourceId): CustomDataSource
    {
        return $this->withoutUndeletedFilter(function () use ($customDataSourceId) {
            return $this->get($customDataSourceId);
        });
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(c.sequence)
                FROM
                    Frontastic\\Catwalk\\FrontendBundle\\Domain\\CustomDataSource c"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(CustomDataSource $customDataSource): CustomDataSource
    {
        $this->manager->persist($customDataSource);
        $this->manager->flush();

        return $customDataSource;
    }

    public function remove(CustomDataSource $customDataSource)
    {
        $customDataSource->isDeleted = true;
        $this->store($customDataSource);
    }

    public function removePermanently(CustomDataSource $customDataSource)
    {
        $this->manager->remove($customDataSource);
        $this->manager->flush();
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
