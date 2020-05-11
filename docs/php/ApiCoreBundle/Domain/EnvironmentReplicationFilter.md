#  EnvironmentReplicationFilter

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\EnvironmentReplicationFilter`](../../../../src/php/ApiCoreBundle/Domain/EnvironmentReplicationFilter.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ReplicatorBundle\Domain\Target $replicationTarget,
    string $applicationEnvironment
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$replicationTarget`|`\Frontastic\Common\ReplicatorBundle\Domain\Target`||
`$applicationEnvironment`|`string`||

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

