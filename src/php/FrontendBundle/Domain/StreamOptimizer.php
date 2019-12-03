<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

interface StreamOptimizer
{
    /**
     * @return mixed
     */
    public function optimizeStreamData(Stream $stream, StreamContext $streamContext, $data);
}
