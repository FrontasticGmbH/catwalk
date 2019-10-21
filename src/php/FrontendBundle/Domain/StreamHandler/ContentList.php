<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\ContentQueryFactory;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;

class ContentList extends StreamHandler
{
    private $contentApi;

    public function __construct(ContentApi $contentApi)
    {
        $this->contentApi = $contentApi;
    }

    public function getType(): string
    {
        return 'content-list';
    }

    public function handleAsync(Stream $stream, Context $context, array $parameters = []): PromiseInterface
    {
        try {
            return $this->contentApi->query(
                ContentQueryFactory::queryFromParameters($stream->configuration),
                $context->locale,
                ContentApi::QUERY_ASYNC
            );
        } catch (\Exception $exception) {
            return Promise\rejection_for($exception);
        }
    }
}
