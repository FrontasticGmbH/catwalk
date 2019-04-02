<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\FrontendBundle\Gateway\RedirectGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;

class RedirectService implements Target
{
    /**
     * @var RedirectGateway
     */
    private $redirectGateway;

    /**
     * @var RedirectCacheService
     */
    private $redirectCacheService;

    public function __construct(RedirectGateway $redirectGateway, RedirectCacheService $redirectCacheService)
    {
        $this->redirectGateway = $redirectGateway;
        $this->redirectCacheService = $redirectCacheService;
    }

    public function lastUpdate(): string
    {
        return $this->redirectGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $redirect = $this->redirectGateway->getEvenIfDeleted($update['redirectId']);
            } catch (\OutOfBoundsException $exception) {
                $redirect = new Redirect([
                    'redirectId' => $update['redirectId'],
                ]);
            }

            $redirect = $this->fill($redirect, $update);
            $this->redirectGateway->store($redirect);
        }

        $this->redirectCacheService->storeRedirects($this->getRedirects());
    }

    public function get(string $redirectId): Redirect
    {
        return $this->redirectGateway->get($redirectId);
    }

    /**
     * @return Redirect[]
     */
    public function getRedirects(): array
    {
        return $this->redirectGateway->getAll();
    }

    protected function fill(Redirect $redirect, array $data): Redirect
    {
        $redirect->sequence = $data['sequence'];
        $redirect->path = $data['path'];
        $redirect->targetType = $data['target']['targetType'];
        $redirect->target = $data['target']['target'];
        $redirect->metaData = $data['metaData'];
        $redirect->isDeleted = (bool)$data['isDeleted'];

        return $redirect;
    }
}
