<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\Node;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\Page;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\Preview;
use Frontastic\Catwalk\FrontendBundle\Domain\PreviewService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Frontastic\Common\ReplicatorBundle\Domain\Result;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @TODO Extract MasterPage related code into service
 */
class PreviewController extends AbstractController
{
    public function viewAction(Request $request, Context $context, string $preview): array
    {
        $previewService = $this->get(PreviewService::class);
        $dataProvider = $this->get(ViewDataProvider::class);

        // @TODO: Build query from request (facet selections, …))
        // $query = new Query();

        $preview = $previewService->get($preview);

        if ($preview->node && $preview->node->isMaster) {
            $this->completeMasterNode($context, $preview->node);
        }

        $data = new \stdClass();
        if ($preview->node) {
            $data = $dataProvider->fetchDataFor($preview->node, $context, [], $preview->page);
        }

        return [
            'previewId' => $preview->previewId,
            'node' => $preview->node,
            'page' => $preview->page,
            'data' => $data,
        ];
    }

    /**
     * TODO: Replace this ugly hack with sane enhancement of page editor in Backstage.
     *
     * The admin should be able to select the product/category to use for preview.
     *
     * @param Node $node
     */
    private function completeMasterNode(Context $context, Node $node): void
    {
        foreach ($node->streams as $streamKey => $stream) {
            if (!isset($stream['configuration'])) {
                continue;
            }

            $pageType = 'product';

            if (strpos($stream['streamId'], '__master') === 0) {
                $pageType = key($stream['configuration']);
            }

            if (array_key_exists($pageType, $stream['configuration']) && $stream['configuration'][$pageType] === null) {
                $itemId = null;
                switch ($pageType) {
                    case 'product':
                        $result = $this
                            ->get('frontastic.catwalk.product_search_api')
                            ->query(new ProductQuery([
                                'locale' => $context->locale,
                            ]))
                            ->wait();
                        $itemId = $result->items[array_rand($result->items)]->productId;
                        break;

                    case 'category':
                    default:
                        $categories = $this->get('frontastic.catwalk.product_api')
                            ->getCategories(new CategoryQuery([
                                'locale' => $context->locale,
                                'limit' => 250,
                            ]));
                        $itemId = $categories[array_rand($categories)]->categoryId;
                        break;
                }

                $node->streams[$streamKey]['configuration'][$pageType] = $itemId;
            }
        }
    }

    public function storeAction(Request $request): JsonResponse
    {
        try {
            $requestVerifier = $this->get(RequestVerifier::class);
            $requestVerifier->ensure($request, $this->getParameter('secret'));

            $previewService = $this->get(PreviewService::class);

            if (!$request->getContent() ||
                !($body = json_decode($request->getContent(), true))) {
                throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
            }
            $previewId = $body['previewId'];

            try {
                $preview = $previewService->get($previewId);
            } catch (\OutOfBoundsException $e) {
                $preview = new Preview(['previewId' => $previewId]);
            }

            $nodeService = $this->get(NodeService::class);
            if ($body['node']) {
                $preview->node = $nodeService->fill(new Node(), $body['node']);
            }

            $pageService = $this->get(PageService::class);
            $preview->page = $pageService->fill(new Page(), $body['page']);

            $preview->createdAt = new \DateTime();
            $preview->metaData = $body['metaData'];

            return new JsonResponse([
                'ok' => true,
                'link' => $this->generateUrl(
                    'Frontastic.Frontend.Preview.view',
                    ['preview' => $preview->previewId],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'preview' => $previewService->store($preview),
            ]);
        } catch (\Throwable $e) {
            $this->get('logger')->error(
                'Error storing the preview: {message}',
                [
                    'message' => $e->getMessage(),
                    'exception' => $e,
                ]
            );
            return new JsonResponse(Result::fromThrowable($e));
        }
    }
}
