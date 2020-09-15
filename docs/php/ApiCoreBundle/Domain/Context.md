#  Context

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\Context`](../../../../src/php/ApiCoreBundle/Domain/Context.php)

**Extends**: [`\Kore\DataObject\DataObject`](https://github.com/kore/DataObject)

Property|Type|Default|Required|Description
--------|----|-------|--------|-----------
`environment` | `string` | `'prod'` | - | Symfony environment
`customer` | `\Frontastic\Common\ReplicatorBundle\Domain\Customer` |  | *Yes* | 
`project` | `\Frontastic\Common\ReplicatorBundle\Domain\Project` |  | *Yes* | 
`projectConfiguration` | `array` | `[]` | *Yes* | 
`projectConfigurationSchema` | `array` | `[]` | *Yes* | 
`locale` | `string` |  | *Yes* | 
`currency` | `string` | `'EUR'` | *Yes* | 
`routes` | `string[]` | `[]` | *Yes* | 
`session` | `\Frontastic\Common\AccountApiBundle\Domain\Session` | `null` | - | 
`featureFlags` | `` | `[]` | - | 
`host` | `string` |  | - | 

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

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
