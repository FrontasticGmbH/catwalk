<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\RemoteDecorators;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCallBuilder;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi\Content;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi\LifecycleEventDecorator\BaseImplementationV2;
use Frontastic\Common\ContentApiBundle\Domain\Query;
use Frontastic\Common\ContentApiBundle\Domain\Result;

class ContentDecorator extends BaseImplementationV2
{
    use DecoratorCallTrait;

    public function beforeGetContentTypes(ContentApi $contentApi): ?array
    {
        return $this->callExpectList(HooksCallBuilder::CONTENT_BEFORE_GET_CONTENT_TYPES, []);
    }

    public function afterGetContentTypes(ContentApi $contentApi, array $contentTypes): ?array
    {
        return $this->callExpectMultipleObjects(
            HooksCallBuilder::CONTENT_AFTER_GET_CONTENT_TYPES,
            [$contentTypes]
        );
    }

    public function beforeGetContent(
        ContentApi $contentApi,
        string $contentId,
        string $locale = null,
        string $mode = ContentApi::QUERY_SYNC
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::CONTENT_BEFORE_GET_CONTENT, [$contentId, $locale, $mode]);
    }

    public function afterGetContent(ContentApi $contentApi, ?Content $content): ?Content
    {
        return $this->callExpectObject(HooksCallBuilder::CONTENT_AFTER_GET_CONTENT, [$content]);
    }

    public function beforeQuery(
        ContentApi $contentApi,
        Query $query,
        string $locale = null,
        string $mode = ContentApi::QUERY_SYNC
    ): ?array {
        return $this->callExpectList(HooksCallBuilder::CONTENT_BEFORE_GET_QUERY, [$query, $locale, $mode]);
    }

    public function afterQuery(ContentApi $contentApi, ?Result $result): ?Result
    {
        return $this->callExpectObject(HooksCallBuilder::CONTENT_AFTER_GET_QUERY, [$result]);
    }
}
