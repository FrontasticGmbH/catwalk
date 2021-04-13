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
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
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
    private PreviewService $previewService;
    private ViewDataProvider $viewDataProvider;
    private ProductSearchApi $productSearchApi;
    private RequestVerifier $requestVerifier;
    private NodeService $nodeService;
    private PageService $pageService;
    private ProductApi $productApi;

    public function __construct(
        PreviewService $previewService,
        ViewDataProvider $viewDataProvider,
        ProductSearchApi $productSearchApi,
        ProductApi $productApi,
        RequestVerifier $requestVerifier,
        NodeService $nodeService,
        PageService $pageService
    ) {
        $this->previewService = $previewService;
        $this->viewDataProvider = $viewDataProvider;
        $this->productSearchApi = $productSearchApi;
        $this->requestVerifier = $requestVerifier;
        $this->nodeService = $nodeService;
        $this->pageService = $pageService;
        $this->productApi = $productApi;
    }

    public function viewAction(Request $request, Context $context, string $preview): array
    {
        // @TODO: Build query from request (facet selections, …))
        // $query = new Query();

        $preview = $this->previewService->get($preview);

        if ($preview->node && $preview->node->isMaster) {
            $this->completeMasterNode($context, $preview->node);
        }

        $data = new \stdClass();
        if ($preview->node) {
            $data = $this->viewDataProvider->fetchDataFor($preview->node, $context, [], $preview->page);
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
                        $result = $this->productSearchApi
                            ->query(new ProductQuery([
                                'locale' => $context->locale,
                            ]))
                            ->wait();
                        $itemId = $result->items[array_rand($result->items)]->productId;
                        break;

                    case 'category':
                    default:
                        $categories = $this->productApi
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
            $this->requestVerifier->ensure($request, $this->getParameter('secret'));

            if (!$request->getContent() ||
                !($body = json_decode($request->getContent(), true))) {
                throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
            }
            $previewId = $body['previewId'];

            try {
                $preview = $this->previewService->get($previewId);
            } catch (\OutOfBoundsException $e) {
                $preview = new Preview(['previewId' => $previewId]);
            }

            if ($body['node']) {
                $preview->node = $this->nodeService->fill(new Node(), $body['node']);
            }

            $preview->page = $this->pageService->fill(new Page(), $body['page']);

            $preview->createdAt = new \DateTime();
            $preview->metaData = $body['metaData'];

            return new JsonResponse([
                'ok' => true,
                'link' => $this->generateUrl(
                    'Frontastic.Frontend.Preview.view',
                    ['preview' => $preview->previewId],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'preview' => $this->previewService->store($preview),
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
