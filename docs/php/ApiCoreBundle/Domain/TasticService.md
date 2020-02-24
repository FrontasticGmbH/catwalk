#  TasticService

Fully Qualified: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\TasticService`](../../../../src/php/ApiCoreBundle/Domain/TasticService.php)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [getAll()](#getall)
* [getTasticsMappedByType()](#gettasticsmappedbytype)
* [get()](#get)
* [store()](#store)
* [remove()](#remove)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\ApiCoreBundle\Gateway\TasticGateway $tasticGateway,
    string $projectPath,
    string $environment
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tasticGateway`|`\Frontastic\Catwalk\ApiCoreBundle\Gateway\TasticGateway`||
`$projectPath`|`string`||
`$environment`|`string`||

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

### getTasticsMappedByType()

```php
public function getTasticsMappedByType(): array
```

Return Value: `array`

### get()

```php
public function get(
    string $tasticId
): Tastic
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tasticId`|`string`||

Return Value: [`Tastic`](Tastic.md)

### store()

```php
public function store(
    Tastic $tastic
): Tastic
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tastic`|[`Tastic`](Tastic.md)||

Return Value: [`Tastic`](Tastic.md)

### remove()

```php
public function remove(
    Tastic $tastic
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$tastic`|[`Tastic`](Tastic.md)||

Return Value: `void`

