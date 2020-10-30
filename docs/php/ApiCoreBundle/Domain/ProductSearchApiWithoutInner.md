#  ProductSearchApiWithoutInner

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ProductSearchApiWithoutInner`](../../../../src/php/ApiCoreBundle/Domain/ProductSearchApiWithoutInner.php)

**Implements**: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`

## Methods

* [__construct()](#__construct)
* [getAggregate()](#getaggregate)
* [query()](#query)
* [getSearchableAttributes()](#getsearchableattributes)
* [getDangerousInnerClient()](#getdangerousinnerclient)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi $aggregate
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$aggregate`|`\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`||

Return Value: `mixed`

### getAggregate()

```php
public function getAggregate(): \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi
```

Return Value: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`

### query()

```php
public function query(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery $query
): \GuzzleHttp\Promise\PromiseInterface
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$query`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery`||

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

### getSearchableAttributes()

```php
public function getSearchableAttributes(): \GuzzleHttp\Promise\PromiseInterface
```

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

### getDangerousInnerClient()

```php
public function getDangerousInnerClient(): mixed
```

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
