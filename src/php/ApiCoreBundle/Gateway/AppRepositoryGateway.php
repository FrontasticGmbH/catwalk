<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\ApiCoreBundle\Domain\AppRepository;

class AppRepositoryGateway
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

    public function get(string $app): AppRepository
    {
        if (($appRepository = $this->repository->findOneByApp($app)) === null) {
            throw new \OutOfBoundsException("AppRepository with ID $app could not be found.");
        }

        return $appRepository;
    }

    public function getHighestSequence(): string
    {
        $query = $this->manager->createQuery(
            "SELECT
                MAX(r.sequence)
            FROM
                Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\AppRepository r"
        );

        return $query->getSingleScalarResult() ?? '0';
    }

    public function store(AppRepository $appRepository): AppRepository
    {
        $this->manager->persist($appRepository);
        $this->manager->flush();

        return $appRepository;
    }

    public function remove(AppRepository $appRepository)
    {
        $this->manager->remove($appRepository);
        $this->manager->flush();
    }
}
