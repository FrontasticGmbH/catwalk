#  Loader

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Routing\Loader`](../../../../src/php/FrontendBundle/Routing/Loader.php)

**Extends**: `\Symfony\Component\Config\Loader\Loader`

## Methods

* [__construct()](#__construct)
* [load()](#load)
* [supports()](#supports)

### __construct()

```php
public function __construct(
    RouteService $routeService,
    string $nodeControllerClass = NodeController::class
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$routeService`|[`RouteService`](../Domain/RouteService.md)||
`$nodeControllerClass`|`string`|`NodeController::class`|

Return Value: `mixed`

### load()

```php
public function load(
    mixed $resource,
    mixed $type = null
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$resource`|`mixed`||
`$type`|`mixed`|`null`|

Return Value: `mixed`

### supports()

```php
public function supports(
    mixed $resource,
    mixed $type = null
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$resource`|`mixed`||
`$type`|`mixed`|`null`|

Return Value: `mixed`

