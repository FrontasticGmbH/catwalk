#  FrontasticReactRouteService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\FrontasticReactRouteService`](../../../../src/php/FrontendBundle/Domain/FrontasticReactRouteService.php)

**Implements**: [`RouteService`](RouteService.md)

## Methods

* [__construct()](#__construct)
* [getRoutes()](#getroutes)
* [storeRoutes()](#storeroutes)
* [rebuildRoutes()](#rebuildroutes)
* [generateRoutes()](#generateroutes)

### __construct()

```php
public function __construct(
    CustomerService $customerService,
    string $cacheDirectory,
    \Frontastic\Catwalk\FrontendBundle\Gateway\FrontendRoutesGateway $frontendRoutesGateway,
    \Symfony\Component\Filesystem\Filesystem $filesystem
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$customerService`|[`CustomerService`](../../ApiCoreBundle/Domain/CustomerService.md)||
`$cacheDirectory`|`string`||
`$frontendRoutesGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\FrontendRoutesGateway`||
`$filesystem`|`\Symfony\Component\Filesystem\Filesystem`||

Return Value: `mixed`

### getRoutes()

```php
public function getRoutes(): array
```

Return Value: `array`

### storeRoutes()

```php
public function storeRoutes(
    array $routes
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$routes`|`array`||

Return Value: `void`

### rebuildRoutes()

```php
public function rebuildRoutes(
    array $nodes
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodes`|`array`||

Return Value: `void`

### generateRoutes()

```php
public function generateRoutes(
    array $nodes
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodes`|`array`||

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
