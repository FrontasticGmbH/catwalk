<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\LifecycleEventDecorator;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools\ClientFactory;
use Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\RawDataService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CoreBundle\Domain\BaseObject;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client as CommerceToolsClient;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Exception\RequestException;

class AccountListener extends BaseImplementation
{
    /**
     * @var array
     */
    private $customerType;

    /**
     * @var CommerceToolsClient
     */
    private $client;

    /**
     * @var RawDataService
     */
    private $rawDataService;

    public function __construct(ClientFactory $clientFactory, RawDataService $rawDataService)
    {
        $this->client = $clientFactory->factorForConfigurationSection('product');
        $this->rawDataService = $rawDataService;
    }

    public function beforeCreate(
        AccountApi $accountApi,
        Account $account,
        ?Cart $cart = null,
        string $locale = null
    ): void {
        if (!($accountApi->getDangerousInnerClient() instanceof CommerceToolsClient)) {
            return;
        }

        $account->rawApiInput = $this->mapAccountRawApiInputData($account);
    }

    public function beforeUpdate(AccountApi $accountApi, Account $account, string $locale = null): void
    {
        if (!($accountApi->getDangerousInnerClient() instanceof CommerceToolsClient)) {
            return;
        }

        $account->rawApiInput = $this->mapAccountRawApiInputActions($account);
    }

    public function beforeAddAddress(
        AccountApi $accountApi,
        Account $account,
        Address $address,
        string $locale = null
    ): void {
        if (!($accountApi->getDangerousInnerClient() instanceof CommerceToolsClient)) {
            return;
        }

        $address->rawApiInput = $this->mapAddressRawInputData($address);
    }

    public function beforeUpdateAddress(
        AccountApi $accountApi,
        Account $account,
        Address $address,
        string $locale = null
    ): void {
        if (!($accountApi->getDangerousInnerClient() instanceof CommerceToolsClient)) {
            return;
        }

        $address->rawApiInput = $this->mapAddressRawInputData($address);
    }

    private function mapAddressRawInputData(BaseObject $baseObject): array
    {
        return $this->rawDataService->extractRawApiInputData($baseObject, RawDataService::COMMERCETOOLS_ADDRESS_FIELDS);
    }

    private function mapAccountRawApiInputData(BaseObject $baseObject): array
    {
        $rawApiInputData = $this->rawDataService->extractRawApiInputData(
            $baseObject,
            RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $baseObject->projectSpecificData['custom'] ?? [];

        if (!empty($customFieldsData)) {
            $rawApiInputData[] = $this->rawDataService->mapCustomFieldsData(
                $this->getCustomerType(),
                json_encode([
                    RawDataService::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME => $customFieldsData,
                ])
            );
        }

        return $rawApiInputData;
    }

    private function mapAccountRawApiInputActions(BaseObject $baseObject): array
    {
        $rawApiInputData = $this->rawDataService->extractRawApiInputData(
            $baseObject,
            RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $baseObject->projectSpecificData['custom'] ?? [];

        return array_merge(
            $this->rawDataService->mapRawDataActions(
                $rawApiInputData,
                RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
            ),
            $this->determineCustomFieldsAction($customFieldsData)
        );
    }

    private function determineCustomFieldsAction(array $customFieldsData): array
    {
        if (empty($customFieldsData)) {
            return [];
        }

        return [
            'action' => 'setCustomField',
            'name' => RawDataService::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME,
            'value' => json_encode($customFieldsData),
        ];
    }

    /**
     * @throws RequestException
     */
    private function getCustomerType(): array
    {
        if ($this->customerType) {
            return $this->customerType;
        }

        try {
            $customerType = $this->client->get('/types/key=' . RawDataService::TYPE_NAME);
        } catch (RequestException $e) {
            $customerType = $this->createCustomerType();
        }

        return $this->customerType = ['id' => $customerType['id']];
    }

    /**
     * @throws RequestException
     */
    private function createCustomerType(): array
    {
        return $this->client->post(
            '/types',
            [],
            [],
            json_encode([
                'key' => RawDataService::TYPE_NAME,
                'name' => ['de' => 'Frontastic Customer'],
                'description' => ['de' => 'Additional data fields'],
                'resourceTypeIds' => ['customer'],
                'fieldDefinitions' => [
                    [
                        'name' => RawDataService::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME,
                        'type' => ['name' => 'String'],
                        'label' => ['de' => 'Data (JSON)'],
                        'required' => false,
                    ],
                ],
            ])
        );
    }

    public function mapCustomFieldDataToAccount(Account $account): Account
    {
        $account->projectSpecificData = json_decode(
            $account->dangerousInnerAccount['custom']['fields']['data'] ?? ''
        );
        return $account;
    }
}
