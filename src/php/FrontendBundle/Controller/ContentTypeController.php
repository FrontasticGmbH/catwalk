<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\DefaultContentApiFactory;

class ContentTypeController
{
    private DefaultContentApiFactory $defaultContentApiFactory;

    public function __construct(DefaultContentApiFactory $defaultContentApiFactory)
    {
        $this->defaultContentApiFactory = $defaultContentApiFactory;
    }

    public function listAction(Context $context): array
    {
        $contentApiFactory = $this->defaultContentApiFactory;
        $contentApi = $contentApiFactory->factor($context->project);

        return [
            'contentTypes' => $contentApi->getContentTypes(),
        ];
    }
}
