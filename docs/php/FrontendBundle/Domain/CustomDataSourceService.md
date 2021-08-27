#  CustomDataSourceService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\CustomDataSourceService`](../../../../src/php/FrontendBundle/Domain/CustomDataSourceService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [fill()](#fill)
* [get()](#get)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\CustomDataSourceGateway $customDataSourceGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$customDataSourceGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\CustomDataSourceGateway`||

Return Value: `mixed`

### lastUpdate()

```php
public function lastUpdate(): string
```

Return Value: `string`

### replicate()

```php
public function replicate(
    array $updates
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$updates`|`array`||

Return Value: `void`

### fill()

```php
public function fill(
    CustomDataSource $customDataSource,
    array $data
): CustomDataSource
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$customDataSource`|[`CustomDataSource`](CustomDataSource.md)||
`$data`|`array`||

Return Value: [`CustomDataSource`](CustomDataSource.md)

### get()

```php
public function get(
    string $environment,
    string $customDataSourceId
): CustomDataSource
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$environment`|`string`||
`$customDataSourceId`|`string`||

Return Value: [`CustomDataSource`](CustomDataSource.md)

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
