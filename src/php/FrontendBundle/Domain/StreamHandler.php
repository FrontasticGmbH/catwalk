<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

abstract class StreamHandler
{
    abstract public function getType(): string;

    abstract public function handle(Stream $stream, Context $context, array $parameters = []);
}
