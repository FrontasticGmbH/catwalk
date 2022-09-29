<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\TidewaysWrapper;

class SpanWrapper
{
    private $span = null;

    public function __construct($span)
    {
        $this->span = $span;
    }

    /**
     * @param array<string, int|string|bool>
     */
    public function annotate(array $annotations)
    {
        if ($this->isTidewaysSpan()) {
            $this->span->annotate($annotations);
        }
    }

    public function finish()
    {
        if ($this->isTidewaysSpan()) {
            $this->span->finish();
        }
    }

    private function isTidewaysSpan(): bool
    {
        return class_exists('Tideways\Profiler') && ($this->span instanceof \Tideways\Profiler\Span);
    }
}
