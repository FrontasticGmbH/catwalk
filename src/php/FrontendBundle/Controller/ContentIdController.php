<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;
use Frontastic\Common\ContentApiBundle\Domain\ContentQueryFactory;
use Symfony\Component\HttpFoundation\Request;

class ContentIdController
{

    private ContentApi $contentApi;

    public function __construct(ContentApi $contentApi)
    {
        $this->contentApi = $contentApi;
    }

    public function listAction(Request $request, Context $context): array
    {

        $query = ContentQueryFactory::queryFromRequest($request);

        return [
            'result' => $this->contentApi->query($query, $context->locale),
        ];
    }
}
