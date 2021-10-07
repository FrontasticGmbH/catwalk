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

    public function storeAll(array $sitemaps): array
    {
        $this->manager->beginTransaction();

        try {
            foreach ($sitemaps as $sitemap) {
                $this->manager->persist($sitemap);
            }
            $this->manager->flush($sitemaps);
            $this->manager->commit();
        } catch (\Throwable $e) {
            $this->manager->rollback();
            throw new \RuntimeException('Storing all sitemaps at once failed.', 0, $e);
        }
        return $sitemaps;
    }
}
