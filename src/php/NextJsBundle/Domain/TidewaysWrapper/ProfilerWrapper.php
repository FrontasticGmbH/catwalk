<?php

namespace Frontastic\Catwalk\NextJsBundle\Domain\TidewaysWrapper;

class ProfilerWrapper
{

    /**
     * Sets the name of the given transaction allowing you to group requests of the same type
     * together.
     * WARNING: Tideways has an upper limit on the number of unique transaction.
     * See (under the endpoints section): https://tideways.com/profiler/pricing#request-based-pricing
     * Doc link: https://support.tideways.com/documentation/setup/configuration/transactions.htm
     * @param string $name
     */
    public static function setTransactionName(string $name)
    {
        if (class_exists('Tideways\Profiler')) {
            \Tideways\Profiler::setTransactionName($name);
        }
    }

    /**
     * Sets a custom variable that will be shown when viewing the trace in Tideways.
     * Doc link: https://support.tideways.com/documentation/reference/php-extension/custom-metadata.html
     * @param string $name
     * @param string|int|float $value
     */
    public static function setCustomVariable(string $name, $value)
    {
        if (class_exists('Tideways\Profiler')) {
            \Tideways\Profiler::setCustomVariable($name, $value);
        }
    }
    /**
     * Creates a custom span on the timeline in the traceview.
     * Doc link: https://support.tideways.com/documentation/reference/php-extension/custom-timespans.html
     * @param string $name
     * @return \Tideways\Profiler\Span
     */
    public static function createSpan(string $name)
    {
        $span = null;
        if (class_exists('Tideways\Profiler')) {
            $span = \Tideways\Profiler::createSpan($name);
        }

        return new SpanWrapper($span);
    }
}
