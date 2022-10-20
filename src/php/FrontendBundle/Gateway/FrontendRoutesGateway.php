<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Frontastic\Catwalk\FrontendBundle\Domain\FrontendRoutes;

class FrontendRoutesGateway
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

    public function get(): FrontendRoutes
    {
        $frontendRoutes = $this->repository->findAll();

        if (count($frontendRoutes) === 0) {
            throw new \OutOfBoundsException('No FrontendRoutes found for this project.');
        }

        return reset($frontendRoutes);
    }

    public function store(FrontendRoutes $frontendRoutes): FrontendRoutes
    {
        $this->manager->persist($frontendRoutes);
        $this->manager->flush();

        return $frontendRoutes;
    }
}
