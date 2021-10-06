<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\FrontendBundle\Domain\Sitemap;

class SitemapGateway
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

    public function store(Sitemap $sitemap): Sitemap
    {
        $this->manager->persist($sitemap);
        $this->manager->flush();

        return $sitemap;
    }
}
