<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator;

abstract class Formatter
{
    /**
     * Encode object graph into a string
     *
     * @param mixed $value
     * @return string
     */
    abstract public function encode($value): string;

    /**
     * Decode string into object graph
     *
     * @param string $value
     * @return mixed
     */
    abstract public function decode(string $value);
}
