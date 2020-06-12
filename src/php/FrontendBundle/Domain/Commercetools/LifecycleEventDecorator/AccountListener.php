<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\LifecycleEventDecorator;

use Frontastic\Catwalk\ApiCoreBundle\Domain\CommerceTools\ClientFactory;
use Frontastic\Common\AccountApiBundle\Domain\Account;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi;
use Frontastic\Common\AccountApiBundle\Domain\AccountApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\AccountApiBundle\Domain\Address;
use Frontastic\Common\CartApiBundle\Domain\Cart;
use Frontastic\Common\CoreBundle\Domain\BaseObject;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Commercetools\Client as CommerceToolsClient;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Exception\RequestException;

class AccountListener extends BaseImplementation implements CommercetoolsListener
{
    /**
     * @var array
     */
    private $customerType;

    /**
     * @var CommerceToolsClient
     */
    private $client;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->client = $clientFactory->factorForConfigurationSection('product');
    }

    public function afterConfirmEmail(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
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

    public function afterCreate(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function beforeUpdate(AccountApi $accountApi, Account $account, string $locale = null): void
    {
        if (!($accountApi->getDangerousInnerClient() instanceof CommerceToolsClient)) {
            return;
        }

        $account->rawApiInput = $this->mapAccountRawApiInputActions($account);
    }

    public function afterUpdate(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterUpdatePassword(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterResetPassword(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterLogin(AccountApi $accountApi, ?Account $account = null): ?Account
    {
        if (!isset($account)) {
            return null;
        }
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterRefreshAccount(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
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

    public function afterAddAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
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

    public function afterUpdateAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterRemoveAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterSetDefaultBillingAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    public function afterSetDefaultShippingAddress(AccountApi $accountApi, Account $account): ?Account
    {
        return $this->mapCustomFieldDataToAccount($account);
    }

    private function mapAddressRawInputData(BaseObject $baseObject): array
    {
        return $this->extractRawApiInputData($baseObject, self::COMMERCETOOLS_ADDRESS_FIELDS);
    }

    private function mapAccountRawApiInputData(BaseObject $baseObject): array
    {
        $rawApiInputData = $this->extractRawApiInputData(
            $baseObject,
            self::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $baseObject->projectSpecificData['custom'] ?? [];

        if (!empty($customFieldsData)) {
            $rawApiInputData['custom'] = [
                'type' => $this->getCustomerType(),
                'fields' => json_encode([
                    self::COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME => $customFieldsData,
                ]),
            ];
        }

        return $rawApiInputData;
    }

    private function mapAccountRawApiInputActions(BaseObject $baseObject): array
    {
        $rawApiInputData = $this->extractRawApiInputData(
            $baseObject,
            self::COMMERCETOOLS_ACCOUNT_FIELDS
        );
        $customFieldsData = $baseObject->projectSpecificData['custom'] ?? [];

        $actions = [];
        foreach ($rawApiInputData as $fieldKey => $fieldValue) {
            $actions[] = [
                'action' => $this->determineAction($fieldKey, self::COMMERCETOOLS_ACCOUNT_FIELDS),
                $fieldKey => $fieldValue
            ];
        }

        return array_merge($actions, $this->determineCustomFieldsAction($customFieldsData));
    }

    private function extractRawApiInputData(BaseObject $baseObject, array $commerceToolsFields): array
    {
        $rawApiInputData = [];

        foreach ($commerceToolsFields as $fieldKey => $value) {
            // CommerceTools has it, but we don't map
            if (key_exists($fieldKey, $baseObject->projectSpecificData)) {
                $rawApiInputData[$fieldKey] = $baseObject->projectSpecificData[$fieldKey];
            }
        }

        return $rawApiInputData;
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

    private function determineAction(string $fieldKey, array $commerceToolsFields): string
    {
        if (!array_key_exists($fieldKey, $commerceToolsFields)) {
            throw new \InvalidArgumentException('Unknown CommerceTools property: ' . $fieldKey);
        }

        return $commerceToolsFields[$fieldKey][self::COMMERCETOOLS_ACTION_NAME_KEY];
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

    private function mapCustomFieldDataToAccount(Account $account): Account
    {
        $account->projectSpecificData = json_decode(
            $account->dangerousInnerAccount['custom']['fields']['data'] ?? ''
        );
        return $account;
    }
}
