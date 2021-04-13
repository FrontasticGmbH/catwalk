<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Session;
use Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory;
use Frontastic\Common\ReplicatorBundle\Domain\RequestVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AttributesController extends AbstractController
{
    private RequestVerifier $requestVerifier;
    private ProductSearchApiFactory $productSearchApiFactory;

    public function __construct(
        RequestVerifier $requestVerifier,
        ProductSearchApiFactory $productSearchApiFactory
    ) {
        $this->requestVerifier = $requestVerifier;
        $this->productSearchApiFactory = $productSearchApiFactory;
    }

    public function searchableAttributesAction(Request $request, Context $context): array
    {
        $requestVerifier = $this->requestVerifier;
        $requestVerifier->ensure($request, $this->getParameter('secret'));

        /* HACK: This request is stateless, so let the ContextService know that we do not need a session. */
        $request->attributes->set(Session::STATELESS, true);

        $productSearchApi = $this->productSearchApiFactory->factor($context->project);

        $attributes = $productSearchApi->getSearchableAttributes()->wait();

        return [
            'attributes' => $attributes,
            'ok' => true,
        ];
    }
}
