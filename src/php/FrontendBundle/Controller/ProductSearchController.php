<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQueryFactory;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Frontastic\Common\CoreBundle\Domain\Json\Json;

class ProductSearchController extends AbstractController
{

    private ProductSearchApi $productSearchApi;

    public function __construct(ProductSearchApi $productSearchApi)
    {
        $this->productSearchApi = $productSearchApi;
    }

    public function listAction(Request $request, Context $context): array
    {
        $productSearchApi = $this->productSearchApi;

        $query = ProductQueryFactory::queryFromParameters(
            ['locale' => $context->locale],
            $request->getContent() ? Json::decode($request->getContent(), true) : []
        );

        return [
            'result' => $productSearchApi->query($query)->wait(),
        ];
    }
}
