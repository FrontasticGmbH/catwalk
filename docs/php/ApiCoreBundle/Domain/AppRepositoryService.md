#  AppRepositoryService

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\AppRepositoryService`](../../../../src/php/ApiCoreBundle/Domain/AppRepositoryService.php)

## Methods

* [__construct()](#__construct)
* [getProperties()](#getproperties)
* [update()](#update)
* [getRepository()](#getrepository)
* [getFullyQualifiedClassName()](#getfullyqualifiedclassname)

### __construct()

```php
public function __construct(
    \Doctrine\ORM\EntityManager $entityManager,
    \Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway $appGateway,
    \Psr\Log\LoggerInterface $logger,
    string $sourceDir = ''
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$entityManager`|`\Doctrine\ORM\EntityManager`||
`$appGateway`|`\Frontastic\Catwalk\ApiCoreBundle\Gateway\AppGateway`||
`$logger`|`\Psr\Log\LoggerInterface`||
`$sourceDir`|`string`|`''`|

Return Value: `mixed`

### getProperties()

```php
public function getProperties(
    App $app
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$app`|[`App`](App.md)||

Return Value: `array`

### update()

```php
public function update(
    App $app
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$app`|[`App`](App.md)||

Return Value: `mixed`

### getRepository()

```php
public function getRepository(
    string $className
): ?DataRepository
```

*Create data repository for $className.*

While the custom app is still not synchronized, this will return null.
Please make sure your code works accordingly!

Argument|Type|Default|Description
--------|----|-------|-----------
`$className`|`string`||

Return Value: ?[`DataRepository`](DataRepository.md)

### getFullyQualifiedClassName()

```php
public function getFullyQualifiedClassName(
    string $identifier
): string
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$identifier`|`string`||

Return Value: `string`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
