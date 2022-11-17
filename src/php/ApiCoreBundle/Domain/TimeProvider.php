<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

class TimeProvider
{
    public function microtimeNow(): float
    {
        return microtime(true);
    }
}
