<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain;

class RandomValueProvider
{
    public function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
