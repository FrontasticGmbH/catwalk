<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\Query;

use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;

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

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        if (!isset($stream->configuration['content'])) {
            return null;
        }

        return $this->contentApi->getContent($stream->configuration['content']);
    }
}
