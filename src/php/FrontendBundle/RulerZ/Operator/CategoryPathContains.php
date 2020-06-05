<?php

namespace Frontastic\Catwalk\FrontendBundle\RulerZ\Operator;

class CategoryPathContains
{
    public function __invoke(string $categoryPath, string $ancestorId)
    {
        $categoryIds = array_values(array_filter(explode('/', $categoryPath)));
        return in_array($ancestorId, $categoryIds);
    }
}
