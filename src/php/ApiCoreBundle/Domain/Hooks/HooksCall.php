<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Frontastic\Common\CoreBundle\Domain\Json\Json;

class HooksCall
{

    private string $name;
    private string $project;
    private array $headers;
    private array $arguments;

    public function __construct(string $project, string $name, array $arguments)
    {
        $this->name = $name;
        $this->project = $project;
        $this->headers = [];
        $this->arguments = $arguments;
    }

    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $key . ':' . $value;
    }

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
        return Json::encode([
            'arguments' => $this->arguments,
        ]);
    }
}
