#  AppDataFilter

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\AppDataFilter`](../../../../src/php/FrontendBundle/Domain/AppDataFilter.php)

## Methods

* [__construct()](#__construct)
* [filterAppData()](#filterappdata)

### __construct()

```php
public function __construct(
    array $keysToKeepList = [],
    array $keysToAlwaysRemoveList = [],
    array $propertiesToAlwaysRemoveList = []
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$keysToKeepList`|`array`|`[]`|
`$keysToAlwaysRemoveList`|`array`|`[]`|
`$propertiesToAlwaysRemoveList`|`array`|`[]`|

Return Value: `mixed`

### filterAppData()

```php
public function filterAppData(
    array $appData
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$appData`|`array`||

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
