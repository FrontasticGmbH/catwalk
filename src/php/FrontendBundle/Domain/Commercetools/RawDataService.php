<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Commercetools;

use Frontastic\Common\CoreBundle\Domain\ApiDataObject;

class RawDataService
{
    const COMMERCETOOLS_ACCOUNT_FIELDS = [
        'vatId' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setVatId',
        ],
        'customerNumber' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setCustomerNumber',
        ],
    ];

    const COMMERCETOOLS_ADDRESS_FIELDS = [
        'key' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'title' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'region' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'state' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'company' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'department' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'building' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'apartment' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'pOBox' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'mobile' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'email' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
        'fax' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => null,
        ],
    ];

    const COMMERCETOOLS_CART_FIELDS = [
        'country' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setCountry',
        ],
        'customerGroup' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setCustomerGroup',
        ],
        'taxMode' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'changeTaxMode',
        ],
        'taxRoundingMode' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'changeTaxRoundingMode',
        ],
        'shippingRateInput' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setShippingRateInput',
        ],
        'taxCalculationMode' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'changeTaxCalculationMode',
        ],
        'deleteDaysAfterLastModification' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setDeleteDaysAfterLastModification',
        ],
        'itemShippingAddresses' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'updateItemShippingAddress',
        ],
    ];

    const COMMERCETOOLS_LINE_ITEM_FIELDS = [];

    const COMMERCETOOLS_PAYMENT_FIELDS = [
        'customer' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setCustomer',
        ],
        'anonymousId' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setAnonymousId',
        ],
        'interfaceId' => [
            self::COMMERCETOOLS_ACTION_NAME_KEY => 'setInterfaceId',
        ],
    ];

    const COMMERCETOOLS_ACTION_NAME_KEY = 'actionName';

    const COMMERCETOOLS_CUSTOMER_TYPE_FIELD_NAME = 'data';

    const TYPE_NAME = 'frontastic-customer-type';

    public function extractRawApiInputData(ApiDataObject $apiDataObject, array $commerceToolsFields): array
    {
        $rawApiInputData = [];

        foreach ($commerceToolsFields as $fieldKey => $value) {
            // CommerceTools has it, but we don't map
            if (key_exists($fieldKey, $apiDataObject->projectSpecificData)) {
                $rawApiInputData[$fieldKey] = $apiDataObject->projectSpecificData[$fieldKey];
            }
        }

        return $rawApiInputData;
    }

    public function mapRawDataActions(array $rawApiInputData, array $commerceToolsFields): array
    {
        $actions = [];
        foreach ($rawApiInputData as $fieldKey => $fieldValue) {
            $actions[] = [
                'action' => $this->determineAction($fieldKey, $commerceToolsFields),
                $fieldKey => $fieldValue
            ];
        }

        return $actions;
    }

    public function determineAction(string $fieldKey, array $commerceToolsFields): string
    {
        if (!array_key_exists($fieldKey, $commerceToolsFields)) {
            throw new \InvalidArgumentException('Unknown CommerceTools property: ' . $fieldKey);
        }

        return $commerceToolsFields[$fieldKey][RawDataService::COMMERCETOOLS_ACTION_NAME_KEY];
    }

    /**
     * @param array $type
     * @param string|array $customFieldsData
     *
     * @return array
     */
    public function mapCustomFieldsData(array $type, $customFieldsData): array
    {
        return [
            'custom' => [
                'type' => $type,
                'fields' => $customFieldsData,
            ],
        ];
    }
}
