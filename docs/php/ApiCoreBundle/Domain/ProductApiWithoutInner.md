#  ProductApiWithoutInner

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ProductApiWithoutInner`](../../../../src/php/ApiCoreBundle/Domain/ProductApiWithoutInner.php)

**Implements**: `\Frontastic\Common\ProductApiBundle\Domain\ProductApi`

This makes sure that the dangerousInnerProduct as well as
dangerousInnerVariant are removed. This improves caching, as we experienced a
lot of CPU time being consumed while caching the large innerProduct. It also
reduces the cache size. If you need to access dangerousInner*, try doing this
in a lifecycle event listener.

## Methods

* [__construct()](#__construct)
* [getCategories()](#getcategories)
* [getProductTypes()](#getproducttypes)
* [getProduct()](#getproduct)
* [query()](#query)
* [getDangerousInnerClient()](#getdangerousinnerclient)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $innerProductApi
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$innerProductApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||

Return Value: `mixed`

### getCategories()

```php
public function getCategories(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery $query
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery`||

Return Value: `array`

### getProductTypes()

```php
public function getProductTypes(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery $query
): array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery`||

Return Value: `array`

### getProduct()

```php
public function getProduct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query,
    string $mode = self::QUERY_SYNC
): ?object
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`||
`$mode`|`string`|`self::QUERY_SYNC`|One of the QUERY_* connstants. Execute the query synchronously or asynchronously?

Return Value: `?object`

### query()

```php
public function query(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query,
    string $mode = self::QUERY_SYNC
): object
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`||
`$mode`|`string`|`self::QUERY_SYNC`|One of the QUERY_* connstants. Execute the query synchronously or asynchronously?

Return Value: `object`

### getDangerousInnerClient()

```php
public function getDangerousInnerClient(): mixed
```

*Get *dangerous* inner client*

This method exists to enable you to use features which are not yet part
of the abstraction layer.

Be aware that any usage of this method might seriously hurt backwards
compatibility and the future abstractions might differ a lot from the
vendor provided abstraction.

Use this with care for features necessary in your customer and talk with
Frontastic about provising an abstraction.

Return Value: `mixed`

