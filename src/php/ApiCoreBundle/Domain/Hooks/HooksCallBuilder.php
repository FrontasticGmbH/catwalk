<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\CoreBundle\Domain\Json\Json;

class HooksCallBuilder
{
    private string $name;
    private string $project;
    private Context $context;
    private array $arguments;
    private array $headers = [];
    private $serializer;

    public function __construct($serializer)
    {
        $this->serializer = $serializer;
    }

    public function name(string $name)
    {
        $this->name = $name;
    }

    public function project(string $project)
    {
        $this->project = $project;
    }

    public function context($context)
    {
        $this->context = $context;
    }

    public function arguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function header(string $key, $value)
    {
        $this->headers[$key] = $key . ':' . $value;
    }

    public function build(): HooksCall
    {
        $call = new HooksCall();
        $call->name = $this->name;
        $call->project = $this->project;
        $call->headers = $this->headers;

        $serializer = $this->serializer;
        $call->payload = Json::encode($serializer([
            'arguments' => $this->arguments,
            'context' => $this->context,
        ]));
        return $call;
    }
}
