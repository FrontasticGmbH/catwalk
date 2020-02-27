#  AppDataTarget

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\AppDataTarget`](../../../../src/php/ApiCoreBundle/Domain/AppDataTarget.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)

### __construct()

```php
public function __construct(
    AppService $appService,
    AppRepositoryService $appRepositoryService,
    \Frontastic\Catwalk\ApiCoreBundle\Gateway\AppRepositoryGateway $appRepositoryGateway,
    Context $context
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$appService`|[`AppService`](AppService.md)||
`$appRepositoryService`|[`AppRepositoryService`](AppRepositoryService.md)||
`$appRepositoryGateway`|`\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppRepositoryGateway`||
`$context`|[`Context`](Context.md)||

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

