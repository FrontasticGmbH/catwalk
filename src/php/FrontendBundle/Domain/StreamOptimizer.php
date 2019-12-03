<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface StreamOptimizer
{
    public function optimizeStreamData(Stream $stream, StreamContext $streamContext, $data);
}
