#  ProductApiDecorator

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\ProductApiDecorator`](../../../../../src/php/ApiCoreBundle/Domain/RemoteDecorator/ProductApiDecorator.php)

**Extends**: `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\LifecycleEventDecorator\BaseImplementation`

This class executes REST calls using a configured formatter and configured
endpoint URLs.

## Methods

* [__construct()](#__construct)
* [beforeGetCategories()](#beforegetcategories)
* [afterGetCategories()](#aftergetcategories)
* [beforeGetProductTypes()](#beforegetproducttypes)
* [afterGetProductTypes()](#aftergetproducttypes)
* [beforeGetProduct()](#beforegetproduct)
* [afterGetProduct()](#aftergetproduct)
* [beforeQuery()](#beforequery)
* [afterQuery()](#afterquery)

### __construct()

```php
public function __construct(
    RemoteDecoratorFactory $factory,
    \Frontastic\Common\HttpClient $httpClient,
    array $formatter = []
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$factory`|[`RemoteDecoratorFactory`](../RemoteDecoratorFactory.md)||
`$httpClient`|`\Frontastic\Common\HttpClient`||
`$formatter`|`array`|`[]`|

Return Value: `mixed`

### beforeGetCategories()

```php
public function beforeGetCategories(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery $query
): ?array
```

*Before Decorator for getCategories*

Adapt the categories query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery`||

Return Value: `?[\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery]`

### afterGetCategories()

```php
public function afterGetCategories(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    array $categories
): ?array
```

*After Decorator for getCategories*

Adapt the categories result. If nothing is returned the original result
will be used. The URL and method can actually be configured by you.

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$categories`|`array`||

Return Value: `?array`

### beforeGetProductTypes()

```php
public function beforeGetProductTypes(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery $query
): ?array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery`||

Return Value: `?array`

### afterGetProductTypes()

```php
public function afterGetProductTypes(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    array $productTypes
): ?array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$productTypes`|`array`||

Return Value: `?array`

### beforeGetProduct()

```php
public function beforeGetProduct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query,
    string $mode = ProductApi::QUERY_SYNC
): ?array
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`||
`$mode`|`string`|`ProductApi::QUERY_SYNC`|

Return Value: `?array`

### afterGetProduct()

```php
public function afterGetProduct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    ?\Frontastic\Common\ProductApiBundle\Domain\Product $product
): ?\Frontastic\Common\ProductApiBundle\Domain\Product
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$product`|`?\Frontastic\Common\ProductApiBundle\Domain\Product`||

Return Value: `?\Frontastic\Common\ProductApiBundle\Domain\Product`

### beforeQuery()

```php
public function beforeQuery(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query,
    string $mode = ProductApi::QUERY_SYNC
): ?array
```

*Before Decorator for query*

Adapt the product query before the query is executed against the
backend. If nothing is returned the original arguments will be used.
The URL and method can actually be configured by you.

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`||
`$mode`|`string`|`ProductApi::QUERY_SYNC`|

Return Value: `?[\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery]`

### afterQuery()

```php
public function afterQuery(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi $productApi,
    ?\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result $result
): ?\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result
```

*After Decorator for query*

Adapt the product query result. If nothing is returned the original
result will be used. The URL and method can actually be configured by
you.

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApi`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi`||
`$result`|`?\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result`||

Return Value: `?\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result{ items: \Frontastic\Common\ProductApiBundle\Domain\Product[] }`

