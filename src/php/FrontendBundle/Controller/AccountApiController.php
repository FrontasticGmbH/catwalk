<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Frontastic\Common\CoreBundle\Domain\Json\Json;

class AccountApiController extends Controller
{
    /**
     * Add a new address to account
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/address/new",
     *  "Address"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function addAddressAction(Request $request, Context $context): JsonResponse
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }

        $accountApi = $this->get(
            'Frontastic\Common\AccountApiBundle\Domain\AccountApiFactory'
        )->factor($context->project);
        $address = Address::newWithProjectSpecificData($this->getJsonBody($request));
        $account = $accountApi->addAddress($context->session->account, $address);

        return new JsonResponse($account, 200);
    }

    /**
     * Update address information
     *
     * Requires the addressId to be specified in the address to update
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/address/update",
     *  "Address"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function updateAddressAction(Request $request, Context $context): JsonResponse
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }

        $accountApi = $this->get(
            'Frontastic\Common\AccountApiBundle\Domain\AccountApiFactory'
        )->factor($context->project);
        $address = Address::newWithProjectSpecificData($this->getJsonBody($request));
        $account = $accountApi->updateAddress($context->session->account, $address);

        return new JsonResponse($account, 200);
    }

    /**
     * Remove address information
     *
     * Requires (only) the addressId to be specified in the address to remove
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/address/remove",
     *  "Address"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function removeAddressAction(Request $request, Context $context): JsonResponse
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }

        $accountApi = $this->get(
            'Frontastic\Common\AccountApiBundle\Domain\AccountApiFactory'
        )->factor($context->project);
        $address = Address::newWithProjectSpecificData($this->getJsonBody($request));
        $accountApi->removeAddress($context->session->account, $address->addressId);

        return new JsonResponse([], 200);
    }

    /**
     * Set an address as default billing address
     *
     * Requires (only) the addressId to be specified in the address
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/address/setDefaultBilling",
     *  "Address"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function setDefaultBillingAddressAction(Request $request, Context $context): JsonResponse
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }

        $accountApi = $this->get(
            'Frontastic\Common\AccountApiBundle\Domain\AccountApiFactory'
        )->factor($context->project);
        $address = Address::newWithProjectSpecificData($this->getJsonBody($request));
        $account = $accountApi->setDefaultBillingAddress($context->session->account, $address->addressId);

        return new JsonResponse($account, 200);
    }

    /**
     * Set an address as default shipping address
     *
     * Requires (only) the addressId to be specified in the address
     *
     * @Docs\Request(
     *  "POST",
     *  "/api/account/address/setDefaultShipping",
     *  "Address"
     * )
     * @Docs\Response(
     *  "200",
     *  "Account"
     * )
     */
    public function setDefaultShippingAddressAction(Request $request, Context $context): JsonResponse
    {
        if (!$context->session->loggedIn) {
            throw new AuthenticationException('Not logged in.');
        }

        $accountApi = $this->get(
            'Frontastic\Common\AccountApiBundle\Domain\AccountApiFactory'
        )->factor($context->project);
        $address = Address::newWithProjectSpecificData($this->getJsonBody($request));
        $account = $accountApi->setDefaultShippingAddress($context->session->account, $address->addressId);

        return new JsonResponse($account, 200);
    }

    private function getJsonBody(Request $request): array
    {
        if (!$request->getContent() ||
            !($body = Json::decode($request->getContent(), true))) {
            throw new \InvalidArgumentException("Invalid data passed: " . $request->getContent());
        }

        return $body;
    }
}
