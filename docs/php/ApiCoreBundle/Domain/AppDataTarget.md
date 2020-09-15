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
    \Frontastic\Common\ReplicatorBundle\Domain\Project $project
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$appService`|[`AppService`](AppService.md)||
`$appRepositoryService`|[`AppRepositoryService`](AppRepositoryService.md)||
`$appRepositoryGateway`|`\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppRepositoryGateway`||
`$project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||

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

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
