<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AttributesController extends AbstractController
{
    private RequestVerifier $requestVerifier;
    private ProductSearchApi $productSearchApi;

    public function __construct(
        RequestVerifier $requestVerifier,
        ProductSearchApi $productSearchApi
    ) {
        $this->requestVerifier = $requestVerifier;
        $this->productSearchApi = $productSearchApi;
    }

    public function searchableAttributesAction(Request $request, Context $context): array
    {
        $requestVerifier = $this->requestVerifier;
        $requestVerifier->ensure($request, $context->customer->secret);

        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);

        $attributes = $this->productSearchApi->getSearchableAttributes()->wait();

        return [
            'attributes' => $attributes,
            'ok' => true,
        ];
    }
}
