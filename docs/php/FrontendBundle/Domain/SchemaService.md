#  SchemaService

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\SchemaService`](../../../../src/php/FrontendBundle/Domain/SchemaService.php)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [decorate()](#decorate)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway $schemaGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$schemaGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\SchemaGateway`||

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

### decorate()

```php
public function decorate(
    Context $context
): Context
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: [`Context`](../../ApiCoreBundle/Domain/Context.md)

