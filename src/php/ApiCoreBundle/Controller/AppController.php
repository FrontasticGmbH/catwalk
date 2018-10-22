<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;

class AppController extends Controller
{
    public function dataAction(string $app, Context $context, Request $request): JsonResponse
    {
        $repository = $this->get('Frontastic\Catwalk\ApiCoreBundle\Domain\AppRepositoryService')->getRepository($app);

        $orderBy = $request->get('orderBy', []);
        $offset = $request->get('offset', null);
        $limit = $request->get('limit', null);

        return new JsonResponse(
            $repository->findBy(
                array_merge(
                    ['locale' => $context->locale],
                    array_filter(
                        $request->query->all(),
                        function (string $key): bool {
                            return in_array($key, ['orderBy', 'offset', 'limit'], true);
                        }
                    )
                ),
                $orderBy,
                $limit,
                $offset
            )
        );
    }

    public function getAction(string $app, string $dataId, Context $context): JsonResponse
    {
        $repository = $this->get('Frontastic\Catwalk\ApiCoreBundle\Domain\AppRepositoryService')->getRepository($app);

        return new JsonResponse(
            $repository->findOneBy(['locale' => $context->locale, 'dataId' => $dataId])
        );
    }
}
