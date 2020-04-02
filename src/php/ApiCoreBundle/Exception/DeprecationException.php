<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Exception;

class DeprecationException extends \LogicException
{
    public static function withSuggestion(string $problem, string $fixSuggestion): self
    {
        return new self(sprintf('%s (to fix this: %s)', $problem, $fixSuggestion));
    }
}
