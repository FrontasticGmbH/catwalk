<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\ApiCoreBundle\Domain\App;

class AppGateway
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

    /**
     * @return App[]
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    public function get(string $appId): App
    {
        if (($app = $this->repository->findOneByAppId($appId)) === null) {
            throw new \OutOfBoundsException("App with ID $appId could not be found.");
        }

        return $app;
    }

    public function getByIdentifier(string $identifier): App
    {
        if (($app = $this->repository->findOneByIdentifier($identifier)) === null) {
            throw new \OutOfBoundsException("App with identifier $identifier could not be found.");
        }

        return $app;
    }

    public function getHighestSequence(): string
    {
        $query = $this->manager->createQuery(
            "SELECT
                MAX(a.sequence)
            FROM
                Frontastic\\Catwalk\\ApiCoreBundle\\Domain\\App a"
        );

        return $query->getSingleScalarResult() ?? '0';
    }

    public function store(App $app): App
    {
        $this->manager->persist($app);
        $this->manager->flush();

        return $app;
    }

    public function remove(App $app)
    {
        $this->manager->remove($app);
        $this->manager->flush();
    }
}
