<?php

namespace Frontastic\Catwalk\FrontendBundle\Domain\Commercetools;

use Frontastic\Common\CoreBundle\Domain\ApiDataObject;

class RawDataService
{
    const COMMERCETOOLS_ACCOUNT_FIELDS = [];

    const COMMERCETOOLS_ADDRESS_FIELDS = [];

    const COMMERCETOOLS_CART_FIELDS = [];

    const COMMERCETOOLS_LINE_ITEM_FIELDS = [];

    const COMMERCETOOLS_PAYMENT_FIELDS = [];

    const COMMERCETOOLS_ACTION_NAME_KEY = 'actionName';

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
