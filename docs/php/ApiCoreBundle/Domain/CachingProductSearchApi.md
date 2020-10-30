#  CachingProductSearchApi

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\CachingProductSearchApi`](../../../../src/php/ApiCoreBundle/Domain/CachingProductSearchApi.php)

**Extends**: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiBase`

## Methods

* [__construct()](#__construct)
* [getAggregate()](#getaggregate)
* [getDangerousInnerClient()](#getdangerousinnerclient)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi $aggregate,
    \Psr\SimpleCache\CacheInterface $cache,
    mixed $debug = false
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$aggregate`|`\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`||
`$cache`|`\Psr\SimpleCache\CacheInterface`||
`$debug`|`mixed`|`false`|

Return Value: `mixed`

### getAggregate()

```php
public function getAggregate(): \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi
```

Return Value: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`

### getDangerousInnerClient()

```php
public function getDangerousInnerClient(): mixed
```

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
