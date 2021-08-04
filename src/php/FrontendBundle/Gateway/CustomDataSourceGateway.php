<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Assert\Assertion;
use Doctrine\ODM\CouchDB\DocumentManager;
use Doctrine\ODM\CouchDB\DocumentRepository;
use Frontastic\Catwalk\FrontendBundle\Domain\CustomDataSource;

class CustomDataSourceGateway
{
    /**
     * Document repository
     *
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * Document manager
     *
     * @var DocumentManager
     */
    protected $manager;

    public function __construct(DocumentRepository $repository, DocumentManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function getById(string $customDataSourceId): CustomDataSource
    {
        $customDataSource = $this->repository->find($customDataSourceId);
        if ($customDataSource === null) {
            throw new \OutOfBoundsException(
                "CustomDataSource with ID $customDataSourceId could not be found."
            );
        }
        Assertion::isInstanceOf($customDataSource, CustomDataSource::class);
        return $customDataSource;
    }

    public function getSince(string $since, int $count): array
    {
        $query = $this->manager->createQuery('customDataSource', 'since');
        $result = $query
            ->onlyDocs(true)
            ->setStartKey($since)
            ->setSkip(0)
            ->setLimit($count)
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->execute();

        return $result->toArray();
    }

    public function getHighestSequence(): string
    {
        return $this->withoutUndeletedFilter(function () {
            $query = $this->manager->createQuery(
                "SELECT
                    MAX(c.sequence)
                FROM
                    Frontastic\\Catwalk\\DeveloperBundle\\Domain\\CustomDataSource c"
            );

            return $query->getSingleScalarResult() ?? '0';
        });
    }

    public function getCountSince(string $since): int
    {
        $query = $this->manager->createQuery('customDataSource', 'since');
        $result = $query
            ->setStartKey($since)
            ->setSkip(0)
            ->setIncludeDocs(false)
            ->setReduce(true)
            ->execute();

        $result = $result->toArray();
        if (!count($result)) {
            return 0;
        }
        return $result[0]['value'];
    }

    public function getLastChange(): \DateTime
    {
        $query = $this->manager->createQuery('customDataSource', 'since');
        $result = $query
            ->onlyDocs(true)
            ->setSkip(0)
            ->setLimit(1)
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->setDescending(true)
            ->execute();

        $result = $result->toArray();
        if (!count($result)) {
            return new \DateTime();
        }

        return $result[0]->metaData->changed ?: new \DateTime();
    }

    public function fetchAll(string $environment): array
    {
        $query = $this->manager->createQuery('customDataSource', 'all');
        $result = $query
            ->onlyDocs(true)
            ->setStartKey([$environment])
            ->setEndKey([$environment, []])
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->execute();

        return $result->toArray();
    }

    public function get(string $environment, string $customDataSourceType): CustomDataSource
    {
        $query = $this->manager->createQuery('customDataSource', 'all');
        $result = $query
            ->onlyDocs(true)
            ->setKey([$environment, $customDataSourceType])
            ->setSkip(0)
            ->setLimit(1)
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->execute();
        $result = $result->toArray();
        if (!count($result)) {
            throw new \OutOfBoundsException(
                "CustomDataSource of type $customDataSourceType in environment $environment could not be found."
            );
        }

        return $result[0];
    }

    public function getByType(string $customDataSourceType): CustomDataSource
    {
        $query = $this->manager->createQuery('customDataSource', 'all_by_type');
        $result = $query
            ->onlyDocs(true)
            ->setKey($customDataSourceType)
            ->setSkip(0)
            ->setLimit(1)
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->execute();
        $result = $result->toArray();
        if (!count($result)) {
            throw new \OutOfBoundsException(
                "CustomDataSource of type $customDataSourceType could not be found."
            );
        }

        return $result[0];
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
