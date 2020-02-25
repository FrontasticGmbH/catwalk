#  ProjectConfigurationService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\ProjectConfigurationService`](../../../../src/php/FrontendBundle/Domain/ProjectConfigurationService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`, [`ContextDecorator`](../../ApiCoreBundle/Domain/ContextDecorator.md)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [decorate()](#decorate)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\ProjectConfigurationGateway $projectConfigurationGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$projectConfigurationGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\ProjectConfigurationGateway`||

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

