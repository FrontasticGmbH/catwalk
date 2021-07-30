<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * @deprecated Use {@link StreamHandlerV2} instead.
 */
abstract class StreamHandler
{
    abstract public function getType(): string;

    /**
     * @deprecated Use and implement the handleAsync. This method only exists for backwards compatibility with old
     *     stream handlers.
     */
    protected function handle(Stream $stream, Context $context, array $parameters = [])
    {
        throw new \RuntimeException('You need to implement handle() or handleAsync()');
    }

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        try {
            return Promise\promise_for($this->handle($stream, $context, $parameters));
        } catch (\Throwable $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
