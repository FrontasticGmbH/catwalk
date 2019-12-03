<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

/**
 * Documentation about using this interface can be found here:
 * https://frontastic.io/docs/catwalk/performance/20_stream_optimization/
 */
interface StreamOptimizer
{
    /**
     * @return mixed
     */
    public function optimizeStreamData(Stream $stream, StreamContext $streamContext, $data);
}
