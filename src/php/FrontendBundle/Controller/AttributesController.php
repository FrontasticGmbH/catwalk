<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Symfony\Component\HttpFoundation\Request;

class AttributesController
{
    private RequestVerifier $requestVerifier;
    private ProductSearchApi $productSearchApi;
    private string $secret;

    public function __construct(
        RequestVerifier $requestVerifier,
        ProductSearchApi $productSearchApi,
        string $secret
    ) {
        $this->requestVerifier = $requestVerifier;
        $this->productSearchApi = $productSearchApi;
        $this->secret = $secret;
    }

    public function searchableAttributesAction(Request $request): array
    {
        $requestVerifier = $this->requestVerifier;
        $requestVerifier->ensure($request, $this->secret);

        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);

        $attributes = $this->productSearchApi->getSearchableAttributes()->wait();

        return [
            'attributes' => $attributes,
            'ok' => true,
        ];
    }
}
