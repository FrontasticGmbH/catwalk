#  UrlGenerator

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Routing\UrlGenerator`](../../../../src/php/FrontendBundle/Routing/UrlGenerator.php)

**Implements**: `\Frontastic\Common\JsonSerializer\ObjectEnhancer`

## Methods

* [__construct()](#__construct)
* [registerRouter()](#registerrouter)
* [enhance()](#enhance)

### __construct()

```php
public function __construct(
    array $objectRouterMap = []
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$objectRouterMap`|`array`|`[]`|

Return Value: `mixed`

### registerRouter()

```php
public function registerRouter(
    string $class,
    mixed $router
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$class`|`string`||
`$router`|`mixed`||

Return Value: `void`

### enhance()

```php
public function enhance(
    mixed $object
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$object`|`mixed`||

Return Value: `array`

