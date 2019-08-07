<?php

namespace Frontastic\Catwalk\FrontendBundle\RulerZ\Operator;

class ArrayContains
{
    public function __invoke(string $haystack, string $needle)
    {
        return sprintf('in_array(%s, %s)', $needle, $haystack);
    }
}
