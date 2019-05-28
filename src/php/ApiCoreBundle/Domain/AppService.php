<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway;

class AppService implements Target
{
    /**
     * @var AppGateway
     */
    private $appGateway;

    /**
     * @var AppRepositoryService
     */
    private $appRepositoryService;

    public function __construct(AppGateway $appGateway, AppRepositoryService $appRepositoryService)
    {
        $this->appGateway = $appGateway;
        $this->appRepositoryService = $appRepositoryService;
    }

    public function lastUpdate(): string
    {
        return $this->appGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $app = $this->appGateway->get($update['appId']);
            } catch (\OutOfBoundsException $e) {
                $app = new App();
                $app->appId = $update['appId'];
            }

            $app = $this->fill($app, $update);
            $this->appRepositoryService->update($app);
            $this->store($app);
        }
    }

    private function fill(App $app, array $data): App
    {
        $app->sequence = $data['sequence'];
        $app->identifier = $data['identifier'];
        $app->name = $data['name'];
        $app->description = $data['description'];
        $app->configurationSchema = $data['configurationSchema'];
        $app->metaData = $data['metaData'];

        return $app;
    }

    /**
     * @return App[]
     */
    public function getAll(): array
    {
        return $this->appGateway->getAll();
    }

    public function get(string $appId): App
    {
        return $this->appGateway->get($appId);
    }

    public function getByIdentifier(string $identifier): App
    {
        return $this->appGateway->getByIdentifier($identifier);
    }

    public function store(App $app): App
    {
        return $this->appGateway->store($app);
    }

    public function remove(App $app): void
    {
        $this->appGateway->remove($app);
    }
}
