<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Common\ContentApiBundle\Domain\DefaultContentApiFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ContentTypeController extends AbstractController
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
