<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class Content extends StreamHandler
{
    private $contentApi;

    public function __construct(ContentApi $contentApi)
    {
        $this->contentApi = $contentApi;
    }

    public function getType(): string
    {
        return 'content';
    }

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        if (!isset($stream->configuration['content'])) {
            return Promise\promise_for(null);
        }

        try {
            return Promise\promise_for(
                $this->contentApi->getContent(
                    $stream->configuration['content'],
                    $context->locale
                )
            );
        } catch (\Exception $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
