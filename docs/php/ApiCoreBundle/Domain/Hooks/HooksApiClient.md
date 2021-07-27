#  HooksApiClient

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksApiClient`](../../../../../src/php/ApiCoreBundle/Domain/Hooks/HooksApiClient.php)

## Methods

* [__construct()](#__construct)
* [getHooks()](#gethooks)
* [callEvent()](#callevent)

### __construct()

```php
public function __construct(
    \Frontastic\Common\HttpClient $httpClient
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$httpClient`|`\Frontastic\Common\HttpClient`||

Return Value: `mixed`

### getHooks()

```php
public function getHooks(
    string $project
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`string`||

Return Value: `array`

### callEvent()

```php
public function callEvent(
    HooksCall $call
): string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$call`|[`HooksCall`](HooksCall.md)||

Return Value: `string`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
