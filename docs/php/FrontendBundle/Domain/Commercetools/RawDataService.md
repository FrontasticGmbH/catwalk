#  RawDataService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\Commercetools\RawDataService`](../../../../../src/php/FrontendBundle/Domain/Commercetools/RawDataService.php)

## Methods

* [extractRawApiInputData()](#extractrawapiinputdata)
* [mapRawDataActions()](#maprawdataactions)
* [determineAction()](#determineaction)
* [mapCustomFieldsData()](#mapcustomfieldsdata)

### extractRawApiInputData()

```php
public function extractRawApiInputData(
    \Frontastic\Common\CoreBundle\Domain\ApiDataObject $apiDataObject,
    array $commerceToolsFields
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$apiDataObject`|`\Frontastic\Common\CoreBundle\Domain\ApiDataObject`||
`$commerceToolsFields`|`array`||

Return Value: `array`

### mapRawDataActions()

```php
public function mapRawDataActions(
    array $rawApiInputData,
    array $commerceToolsFields
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$rawApiInputData`|`array`||
`$commerceToolsFields`|`array`||

Return Value: `array`

### determineAction()

```php
public function determineAction(
    string $fieldKey,
    array $commerceToolsFields
): string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$fieldKey`|`string`||
`$commerceToolsFields`|`array`||

Return Value: `string`

### mapCustomFieldsData()

```php
public function mapCustomFieldsData(
    array $type,
    mixed $customFieldsData
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$type`|`array`||
`$customFieldsData`|`mixed`||

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
