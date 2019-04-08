<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\Redirect;

class RedirectGateway
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

    public function get(string $redirectId): Redirect
    {
        if (($redirect = $this->repository->find($redirectId)) === null) {
            throw new \OutOfBoundsException("Redirect with ID $redirectId could not be found.");
        }

        return $redirect;
    }

    /**
     * @return Redirect[]
     */
    public function getAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @return Redirect[]
     */
    public function getByPath(string $path): array
    {
        return $this->repository->findBy(['path' => $path]);
    }

    public function getHighestSequence(): string
    {
        $query = $this->manager->createQuery(
            "SELECT
                MAX(rd.sequence)
            FROM
                Frontastic\\Catwalk\\FrontendBundle\\Domain\\Redirect rd"
        );

        return $query->getSingleScalarResult() ?? '0';
    }

    public function store(Redirect $redirect): Redirect
    {
        $this->manager->persist($redirect);
        $this->manager->flush();

        return $redirect;
    }

    public function getEvenIfDeleted(string $redirectId): Redirect
    {
        return $this->withoutUndeletedFilter(function () use ($redirectId) {
            return $this->get($redirectId);
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
