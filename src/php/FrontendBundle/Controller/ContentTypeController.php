<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;

class ContentTypeController
{
    private ContentApi $contentApi;

    public function __construct(ContentApi $contentApi)
    {
        $this->contentApi = $contentApi;
    }

    public function listAction(Context $context): array
    {
        return [
            'contentTypes' => $this->contentApi->getContentTypes(),
        ];
    }
}
