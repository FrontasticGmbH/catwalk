#  HooksCall

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Hooks\HooksCall`](../../../../../src/php/ApiCoreBundle/Domain/Hooks/HooksCall.php)

## Methods

* [__construct()](#__construct)
* [addHeader()](#addheader)
* [getProject()](#getproject)
* [getName()](#getname)
* [getHeaders()](#getheaders)
* [getPayload()](#getpayload)

### __construct()

```php
public function __construct(
    string $project,
    string $name,
    array $arguments
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`string`||
`$name`|`string`||
`$arguments`|`array`||

Return Value: `mixed`

### addHeader()

```php
public function addHeader(
    string $key,
    string $value
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$key`|`string`||
`$value`|`string`||

Return Value: `mixed`

### getProject()

```php
public function getProject(): string
```

Return Value: `string`

### getName()

```php
public function getName(): string
```

Return Value: `string`

### getHeaders()

```php
public function getHeaders(): array
```

Return Value: `array`

### getPayload()

```php
public function getPayload(): string
```

Return Value: `string`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
