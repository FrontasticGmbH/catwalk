<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\FrontendBundle\Domain\Node;

class NodeGateway
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

    public function getTree($root = null, $maxDepth = null): array
    {
        $path = '/';
        $minDepth = 0;
        if ($maxDepth === null) {
            $maxDepth = 1024;
        }

        if ($root !== null) {
            $rootNode = $this->get($root);

            $path = rtrim($rootNode->path, '/') . '/' . $rootNode->nodeId;
            $minDepth = $rootNode->depth + 1;
            $maxDepth = $rootNode->depth + 1 + $maxDepth;
        }

        $query = $this->manager->createQuery(
            "SELECT
                n
            FROM
                Frontastic\\Catwalk\\FrontendBundle\\Domain\\Node n
            WHERE
                n.depth >= :minDepth AND
                n.depth < :maxDepth AND
                n.isMaster = 0 AND
                n.path LIKE :path
            ORDER BY
                n.path,
                n.sort"
        );

        $query->setParameter('minDepth', $minDepth);
        $query->setParameter('maxDepth', $maxDepth);
        $query->setParameter('path', $path . '%');

        return isset($rootNode) ? array_merge([$rootNode], $query->getResult()) : $query->getResult();
    }

    public function get(string $nodeId): Node
    {
        if (($node = $this->repository->findOneByNodeId($nodeId)) === null) {
            throw new \OutOfBoundsException("Node with ID $nodeId could not be found.");
        }

        return $node;
    }

    public function getHighestSequence(): string
    {
        $query = $this->manager->createQuery(
            "SELECT
                MAX(n.sequence)
            FROM
                Frontastic\\Catwalk\\FrontendBundle\\Domain\\Node n"
        );

        return $query->getSingleScalarResult() ?? '0';
    }

    public function store(Node $node): Node
    {
        $this->manager->persist($node);
        $this->manager->flush();

        return $node;
    }

    public function remove(Node $node)
    {
        $node->isDeleted = true;
        $this->store($node);
    }

    public function getEvenIfDeleted(string $nodeId): Node
    {
        return $this->withoutUndeletedFilter(function () use ($nodeId) {
            return $this->get($nodeId);
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
