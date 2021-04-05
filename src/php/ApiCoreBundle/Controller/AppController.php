<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\AppRepositoryService;
use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AppController extends AbstractController
{

    private AppRepositoryService $appRepositoryService;

    public function __construct(AppRepositoryService $appRepositoryService)
    {

        $this->appRepositoryService = $appRepositoryService;
    }

    public function dataAction(string $app, Context $context, Request $request): JsonResponse
    {
        $repository = $this->appRepositoryService->getRepository($app);

        if ($repository === null) {
            return new JsonResponse([]);
        }

        $orderBy = $request->get('orderBy', []);
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);

        $criteria = array_merge(
            ['locale' => $context->locale],
            array_filter(
                $request->query->all(),
                function (string $key): bool {
                    return !in_array($key, ['orderBy', 'offset', 'limit'], true);
                },
                ARRAY_FILTER_USE_KEY
            )
        );

        return new JsonResponse(
            $repository->findBy(
                $criteria,
                $orderBy,
                $limit,
                $offset
            )
        );
    }

    public function getAction(string $app, string $dataId, Context $context): JsonResponse
    {
        $repository = $this->appRepositoryService->getRepository($app);

        if ($repository === null) {
            return new JsonResponse([]);
        }

        return new JsonResponse(
            $repository->findOneBy(['locale' => $context->locale, 'dataId' => $dataId])
        );
    }
}
