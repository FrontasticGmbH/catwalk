<?php

namespace Frontastic\Catwalk\FrontendBundle\Gateway;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

use Frontastic\Catwalk\FrontendBundle\Domain\Preview;

class PreviewGateway
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

    public function get(string $previewId): Preview
    {
        if (($preview = $this->repository->findOneByPreviewId($previewId)) === null) {
            throw new \OutOfBoundsException("Preview with ID $previewId could not be found.");
        }

        return $preview;
    }

    public function store(Preview $preview): Preview
    {
        $this->manager->persist($preview);
        $this->manager->flush();

        return $preview;
    }

    public function remove(Preview $preview)
    {
        $this->manager->remove($preview);
        $this->manager->flush();
    }
}
