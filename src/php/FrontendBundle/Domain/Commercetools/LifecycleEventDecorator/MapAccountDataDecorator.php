<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\LifecycleEventDecorator;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools\ClientFactory;
use Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\RawDataService;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CoreBundle\Domain\ApiDataObject;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client as CommerceToolsClient;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Exception\RequestException;

class MapAccountDataDecorator extends BaseImplementation
{
    const COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME = 'data';

    const TYPE_NAME = 'frontastic-customer-type';

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

    private function mapAddressRawInputData(ApiDataObject $apiDataObject): array
    {
        return $this->rawDataService->extractRawApiInputData(
            $apiDataObject,
            RawDataService::COMMERCETOOLS_ADDRESS_FIELDS
        );
    }

    private function mapAccountRawApiInputData(ApiDataObject $apiDataObject): array
    {
        $rawApiInputData = $this->rawDataService->extractRawApiInputData(
            $apiDataObject,
            RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $apiDataObject->projectSpecificData['custom'] ?? [];

        if (!empty($customFieldsData)) {
            $rawApiInputData = array_merge(
                $rawApiInputData,
                $this->rawDataService->mapCustomFieldsData(
                    $this->getCustomerType(),
                    [
                        self::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME => json_encode($customFieldsData),
                    ]
                )
            );
        }

        return $rawApiInputData;
    }

    private function mapAccountRawApiInputActions(ApiDataObject $apiDataObject): array
    {
        $rawApiInputData = $this->rawDataService->extractRawApiInputData(
            $apiDataObject,
            RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $apiDataObject->projectSpecificData['custom'] ?? [];

        return array_merge(
            $this->rawDataService->mapRawDataActions(
                $rawApiInputData,
                RawDataService::COMMERCETOOLS_ACCOUNT_FIELDS
            ),
            [
                $this->determineCustomFieldsAction($customFieldsData)
            ]
        );
    }

    private function determineCustomFieldsAction(array $customFieldsData): array
    {
        if (empty($customFieldsData)) {
            return [];
        }

        return [
            'action' => 'setCustomField',
            'name' => self::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME,
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
            $customerType = $this->client->get('/types/key=' . self::TYPE_NAME);
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
                'key' => self::TYPE_NAME,
                'name' => ['de' => 'Frontastic Customer'],
                'description' => ['de' => 'Additional data fields'],
                'resourceTypeIds' => ['customer'],
                'fieldDefinitions' => [
                    [
                        'name' => self::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME,
                        'type' => ['name' => 'String'],
                        'label' => ['de' => 'Data (JSON)'],
                        'required' => false,
                    ],
                ],
            ])
        );
    }

    public function mapReturnedAccount(Account $account): Account
    {
        $account->projectSpecificData['data'] = json_decode(
            $account->dangerousInnerAccount['custom']['fields']['data'] ?? ''
        );
        return $account;
    }
}
