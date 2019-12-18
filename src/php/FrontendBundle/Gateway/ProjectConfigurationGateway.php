<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\ProjectConfiguration;

class ProjectConfigurationGateway
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

    public function get(string $projectConfigurationId): ProjectConfiguration
    {
        if (($projectConfiguration = $this->repository->find($projectConfigurationId)) === null) {
            throw new \OutOfBoundsException(
                "Project Configuration with ID $projectConfigurationId could not be found."
            );
        }

        return $projectConfiguration;
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(pc.sequence)
                FROM
                    Frontastic\\Catwalk\\FrontendBundle\\Domain\\ProjectConfiguration pc"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function store(ProjectConfiguration $projectConfiguration): ProjectConfiguration
    {
        $this->manager->persist($projectConfiguration);
        $this->manager->flush();

        return $projectConfiguration;
    }

    public function getEvenIfDeleted(string $projectConfigurationId): ProjectConfiguration
    {
        return $this->withoutUndeletedFilter(function () use ($projectConfigurationId) {
            return $this->get($projectConfigurationId);
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
