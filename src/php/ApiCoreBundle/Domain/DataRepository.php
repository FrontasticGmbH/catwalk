<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Doctrine\ORM\EntityRepository;
use Kore\DataObject\DataObject;

class DataRepository extends EntityRepository
{
    public function store(DataObject $data): DataObject
    {
        $this->getEntityManager()->persist($data);
        $this->getEntityManager()->flush();

        return $data;
    }

    public function remove(DataObject $data)
    {
        $this->getEntityManager()->remove($data);
        $this->getEntityManager()->flush();
    }

    public function findOneByEvenIfDeleted(array $criteria, array $orderBy = null)
    {
        return ($this->withoutUndeletedFilter(function () use ($criteria, $orderBy) {
            return $this->findOneBy($criteria, $orderBy);
        }));
    }

    private function withoutUndeletedFilter(callable $callback)
    {
        $this->getEntityManager()->getFilters()->disable('undeleted');
        try {
            return $callback();
        } finally {
            $this->getEntityManager()->getFilters()->enable('undeleted');
        }
    }
}
