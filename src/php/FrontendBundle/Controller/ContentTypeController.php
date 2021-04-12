<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\ContentApiFactory;

class ContentTypeController
{
    private ContentApiFactory $contentApiFactory;

    public function __construct(ContentApiFactory $contentApiFactory)
    {
        $this->contentApiFactory = $contentApiFactory;
    }

    public function listAction(Context $context): array
    {
        $contentApiFactory = $this->contentApiFactory;
        $contentApi = $contentApiFactory->factor($context->project);

        return [
            'contentTypes' => $contentApi->getContentTypes(),
        ];
    }
}
