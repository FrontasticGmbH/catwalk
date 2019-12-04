<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

/**
 * Implementations of this interface are used to optimize a stream for sending it to the view.
 * See the documentation for example and details.
 */
interface StreamOptimizer
{
    /**
     * Optimize the given stream $data and return the optimized version.
     * The information given in $stream and $streamContext refers to the current usage and
     * can deal for optimizing a stream for a dedicated use-case.
     * @return mixed The optimized stream data
     */
    public function optimizeStreamData(Stream $stream, StreamContext $streamContext, $data);
}
