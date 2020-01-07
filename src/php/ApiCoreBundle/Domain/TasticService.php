<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

use Frontastic\Common\ReplicatorBundle\Domain\Target;

use Frontastic\Catwalk\ApiCoreBundle\Gateway\TasticGateway;

class TasticService implements Target
{
    /**
     * @var TasticGateway
     */
    private $tasticGateway;

    /**
     * @var Tastic[]
     */
    private $tastics = null;

    public function __construct(TasticGateway $tasticGateway)
    {
        $this->tasticGateway = $tasticGateway;
    }

    public function lastUpdate(): string
    {
        return $this->tasticGateway->getHighestSequence();
    }

    public function replicate(array $updates): void
    {
        foreach ($updates as $update) {
            try {
                $tastic = $this->tasticGateway->getEvenIfDeleted($update['tasticId']);
            } catch (\OutOfBoundsException $e) {
                $tastic = new Tastic();
                $tastic->tasticId = $update['tasticId'];
            }

            $tastic = $this->fill($tastic, $update);
            $this->store($tastic);
        }
    }

    private function fill(Tastic $tastic, array $data): Tastic
    {
        $tastic->sequence = $data['sequence'];
        $tastic->tasticType = $data['tasticType'];
        $tastic->name = $data['name'];
        $tastic->description = $data['description'];
        $tastic->configurationSchema = $data['configurationSchema'];
        $tastic->metaData = $data['metaData'];
        $tastic->isDeleted = (bool)$data['isDeleted'];

        return $tastic;
    }

    /**
     * @return Tastic[]
     */
    public function getAll(): array
    {
        if ($this->tastics) {
            return $this->tastics;
        }

        return $this->tastics = $this->tasticGateway->getAll();
    }

    /**
     * @return Tastic[]
     */
    public function getTasticsMappedByType(): array
    {
        $allTastics = $this->getAll();

        /** @var Tastic[] $map */
        $map = [];
        foreach ($allTastics as $tastic) {
            if (!isset($map[$tastic->tasticType]) || $map[$tastic->tasticType]->sequence < $tastic->sequence) {
                $map[$tastic->tasticType] = $tastic;
            }
        }
        return $map;
    }

    public function get(string $tasticId): Tastic
    {
        return $this->tasticGateway->get($tasticId);
    }

    public function store(Tastic $tastic): Tastic
    {
        $this->tastics = null;
        return $this->tasticGateway->store($tastic);
    }

    public function remove(Tastic $tastic): void
    {
        $this->tastics = null;
        $this->tasticGateway->remove($tastic);
    }
}
