<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ContentApiBundle\Domain;
use Frontastic\Common\ContentApiBundle\Domain\ContentQueryFactory;
use Frontastic\Common\CoreBundle\Domain\Json\Json;
use Symfony\Component\HttpFoundation\Request;
use Frontastic\Common\ContentApiBundle\Domain\ContentApi;

class ContentSearchController
{
    private ContentApi $contentApi;

    public function __construct(ContentApi $contentApi)
    {
        $this->contentApi = $contentApi;
    }

    public function showAction(Request $request, Context $context): array
    {
        $requestParameters = Json::decode($request->getContent(), true);
        if (isset($requestParameters['contentId'])) {
            $contentId = $requestParameters['contentId'];
            $result = $this->contentApi->getContent($contentId, $context->locale);
        } elseif (isset($requestParameters['contentIds'])) {
            $contentIds = $requestParameters['contentIds'];
            $query = new Domain\Query(['contentIds' => $contentIds]);
            $result = $this->contentApi->query($query, $context->locale);
        } else {
            throw new \RuntimeException("either contentId nor contentIds is set in request");
        }

        return [
            'result' => $result,
        ];
    }

    public function listAction(Request $request, Context $context): array
    {
        $query = ContentQueryFactory::queryFromRequest($request);

        return [
            'result' => $this->contentApi->query($query, $context->locale),
        ];
    }
}
