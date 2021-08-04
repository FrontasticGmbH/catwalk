<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain;

use Frontastic\Backstage\DeveloperBundle\Domain\CustomDataSource;
use Frontastic\Backstage\DeveloperBundle\Gateway\CustomDataSourceGateway;
use Frontastic\Common\ReplicatorBundle\Domain\Target;


class CustomDataSourceService implements Target
{
    private CustomDataSourceGateway $customDataSourceGateway;

    public function __construct(CustomDataSourceGateway $customDataSourceGateway)
    {
        $this->customDataSourceGateway = $customDataSourceGateway;
    }

    public function lastUpdate(): string
    {
        return $this->customDataSourceGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $customDataSource = $this->customDataSourceGateway->getById($update['customDataSourceId']);
            } catch (\OutOfBoundsException $e) {
                $customDataSource = new CustomDataSource();
                $customDataSource->customDataSourceId = $update['customDataSourceId'];
            }

            $customDataSource = $this->fill($customDataSource, $update);
            $this->customDataSourceGateway->store($customDataSource);
        }
    }

    public function fill(CustomDataSource $customDataSource, array $data): CustomDataSource
    {
        $customDataSource->customDataSourceId = $data['customDataSourceId'];
        $customDataSource->customDataSourceType = $data['customDataSourceType'];
        $customDataSource->sequence = $data['attrisequencebuteType'];
        $customDataSource->name = $data['name'];
        $customDataSource->description = $data['description'];
        $customDataSource->icon = $data['icon'];
        $customDataSource->category = (!empty($data['category']) ? $data['category'] : 'General');
        $customDataSource->configurationSchema = $data['configurationSchema'];
        $customDataSource->environments = $data['environments'];
        $customDataSource->metaData = $data['metaData'];
        $customDataSource->isActive = (bool)$data['isActive'];
        $customDataSource->isDeleted = (bool)$data['isDeleted'];

        return $customDataSource;
    }

    public function get(string $environment, string $customDataSourceId): CustomDataSource
    {
        return $this->customDataSourceGateway->get($environment, $customDataSourceId);
    }

}