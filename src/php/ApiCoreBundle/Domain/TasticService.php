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

    /**
     * @var array
     */
    private $tasticSchemaOverwrites = [];

    public function __construct(TasticGateway $tasticGateway, string $projectPath, string $environment)
    {
        $this->tasticGateway = $tasticGateway;
        if ($environment === 'dev') {
            $this->readTasticSchemaOverwrites($projectPath);
        }
    }

    private function readTasticSchemaOverwrites(string $projectPath)
    {
        $baseDirectory = $projectPath . '/config/tasticSchemaOverwrites';
        foreach (glob($baseDirectory . '/*.json') as $schemaFile) {
            $schema = json_decode(file_get_contents($schemaFile), true);
            if (!$schema) {
                debug('Could not parse ' . $schemaFile);
                continue;
            }

            $this->tasticSchemaOverwrites[$schema['tasticType']] = $schema;
        }
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

        $tastics = $this->tasticGateway->getAll();
        foreach ($tastics as $tastic) {
            if (isset($this->tasticSchemaOverwrites[$tastic->tasticType])) {
                $tastic->configurationSchema = $this->tasticSchemaOverwrites[$tastic->tasticType];
            }
        }

        return $this->tastics = $tastics;
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
        $tastic = $this->tasticGateway->get($tasticId);
        if (isset($this->tasticSchemaOverwrites[$tastic->tasticType])) {
            $tastic->schema = $$this->tasticSchemaOverwrites[$tastic->tasticType];
        }

        return $tastic;
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
