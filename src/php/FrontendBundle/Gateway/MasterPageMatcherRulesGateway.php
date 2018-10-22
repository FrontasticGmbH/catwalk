<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterPageMatcherRules;

class MasterPageMatcherRulesGateway
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

    public function store(MasterPageMatcherRules $rules): void
    {
        $this->manager->persist($rules);
        $this->manager->flush();
    }

    public function get(): MasterPageMatcherRules
    {
        $rules = $this->repository->findAll();

        if (count($rules) === 0) {
            throw new \OutOfBoundsException('No MasterPageMatcherRules found for this project.');
        }
        return reset($rules);
    }
}
