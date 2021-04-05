<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Common\ContentApiBundle\Domain;
use Frontastic\Common\ContentApiBundle\Domain\ContentQueryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class ContentIdController extends AbstractController
{
    private Domain\DefaultContentApiFactory $defaultContentApiFactory;

    public function __construct(Domain\DefaultContentApiFactory $defaultContentApiFactory)
    {
        $this->defaultContentApiFactory = $defaultContentApiFactory;
    }

    public function listAction(Request $request, Context $context): array
    {
        $contentApiFactory = $this->defaultContentApiFactory;
        $contentApi = $contentApiFactory->factor($context->project);

        $query = ContentQueryFactory::queryFromRequest($request);

        return [
            'result' => $contentApi->query($query, $context->locale),
        ];
    }
}
