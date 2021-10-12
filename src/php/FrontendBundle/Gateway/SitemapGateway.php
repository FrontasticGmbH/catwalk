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

    public function loadLatestByPath(string $path): ?Sitemap
    {
        $query = $this->manager->createQuery('SELECT s FROM ' . Sitemap::class . ' s' .
            ' WHERE s.filepath = :path ' .
            ' ORDER BY s.generationTimestamp DESC ');
        $query->setMaxResults(1);

        $query->execute(['path' => $path]);

        return $query->getOneOrNullResult();
    }

    public function loadGenerationTimestampsByBasedir(): array
    {
        $query = $this->manager->createQuery('SELECT s.basedir, s.generationTimestamp FROM ' . Sitemap::class . ' s' .
            ' GROUP BY s.basedir, s.generationTimestamp ' .
            ' ORDER BY s.generationTimestamp DESC');
        $query->execute();

        return $query->getArrayResult();
    }

    public function remove(string $basedir, int $timestamp): void
    {
        $query = $this->manager->createQuery('DELETE FROM ' . Sitemap::class . ' s ' .
            'WHERE s.basedir = :basedir AND s.generationTimestamp = :timestamp');
        $query->execute([
            'basedir' => $basedir,
            'timestamp' => $timestamp
        ]);
    }
}
