#  AppService

Fully Qualified: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\AppService`](../../../../src/php/ApiCoreBundle/Domain/AppService.php)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [getAll()](#getall)
* [get()](#get)
* [getByIdentifier()](#getbyidentifier)
* [store()](#store)
* [remove()](#remove)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway $appGateway,
    AppRepositoryService $appRepositoryService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$appGateway`|`\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway`||
`$appRepositoryService`|[`AppRepositoryService`](AppRepositoryService.md)||

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

### getAll()

```php
public function getAll(): array
```

Return Value: `array`

### get()

```php
public function get(
    string $appId
): App
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$appId`|`string`||

Return Value: [`App`](App.md)

### getByIdentifier()

```php
public function getByIdentifier(
    string $identifier
): App
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$identifier`|`string`||

Return Value: [`App`](App.md)

### store()

```php
public function store(
    App $app
): App
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$app`|[`App`](App.md)||

Return Value: [`App`](App.md)

### remove()

```php
public function remove(
    App $app
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$app`|[`App`](App.md)||

Return Value: `void`

