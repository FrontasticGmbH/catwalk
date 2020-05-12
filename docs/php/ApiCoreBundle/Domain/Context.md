#  Context

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Context`](../../../../src/php/ApiCoreBundle/Domain/Context.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Description
--------|----|-------|-----------
`environment`|`string`|`'prod'`|Symfony environment
`customer`|`\Frontastic\Common\ReplicatorBundle\Domain\Customer`||
`project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||
`projectConfiguration`|`array`|`[]`|
`projectConfigurationSchema`|`array`|`[]`|
`locale`|`string`||
`currency`|`string`|`'EUR'`|
`routes`|`string[]`|`[]`|
`session`|`\Frontastic\Common\AccountApiBundle\Domain\Session`|`null`|
`featureFlags`|``|`[]`|
`host`|`string`||

## Methods

* [__construct()](#__construct)
* [applicationEnvironment()](#applicationenvironment)
* [hasFeature()](#hasfeature)
* [isProduction()](#isproduction)

### __construct()

```php
public function __construct(
    array $values = []
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$values`|`array`|`[]`|

Return Value: `mixed`

### applicationEnvironment()

```php
public function applicationEnvironment(): string
```

Return Value: `string`

### hasFeature()

```php
public function hasFeature(
    string $featureFlag
): bool
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$featureFlag`|`string`||

Return Value: `bool`

### isProduction()

```php
public function isProduction(): bool
```

Return Value: `bool`

