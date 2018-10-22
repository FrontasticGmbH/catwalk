<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ODM\CouchDB\DocumentRepository;
use Doctrine\ODM\CouchDB\DocumentManager;

use Frontastic\Catwalk\FrontendBundle\Domain\Layout;

class LayoutGateway
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

    public function fetchAll(): array
    {
        $query = $this->manager->createQuery('layout', 'all');
        $result = $query
            ->setIncludeDocs(true)
            ->setReduce(false)
            ->onlyDocs(true)
            ->execute();

        return $result->toArray();
    }

    public function get(string $layoutId): Layout
    {
        if (($layout = $this->repository->find($layoutId)) === null) {
            throw new \OutOfBoundsException("Layout with ID $layoutId could not be found.");
        }

        return $layout;
    }

    public function store(Layout $layout): Layout
    {
        $this->manager->persist($layout);
        $this->manager->flush();

        return $layout;
    }

    public function remove(Layout $layout)
    {
        $this->manager->remove($layout);
        $this->manager->flush();
    }
}
