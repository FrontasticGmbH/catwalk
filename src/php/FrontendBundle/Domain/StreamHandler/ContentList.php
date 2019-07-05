<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;

use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\Query;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler;
use Frontastic\Catwalk\FrontendBundle\Domain\Stream;

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

    public function handle(Stream $stream, Context $context, array $parameters = [])
    {
        return $this->contentApi->query(Query::fromArray($stream->configuration));
    }
}
