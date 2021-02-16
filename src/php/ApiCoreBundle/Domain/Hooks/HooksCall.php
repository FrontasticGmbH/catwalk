<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Kore\DataObject\DataObject;

class HooksCall extends DataObject
{

    public string $name;
    public string $project;
    public array $headers;
    public string $payload;

    public function getProject(): string
    {
        return $this->project;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayload(): string
    {
        return $this->payload;
    }
}
