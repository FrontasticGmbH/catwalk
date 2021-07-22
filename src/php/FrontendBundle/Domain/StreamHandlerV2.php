<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use GuzzleHttp\Promise\PromiseInterface;
use Symfony\Component\HttpFoundation\Request;

interface StreamHandlerV2
{
    /**
     * @param Tastic[] $usingTastics
     */
    public function handle(Stream $stream, StreamContext $streamContext) : PromiseInterface;
}
