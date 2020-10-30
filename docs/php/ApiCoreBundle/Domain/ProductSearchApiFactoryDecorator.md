#  ProductSearchApiFactoryDecorator

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ProductSearchApiFactoryDecorator`](../../../../src/php/ApiCoreBundle/Domain/ProductSearchApiFactoryDecorator.php)

**Implements**: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory`

## Methods

* [__construct()](#__construct)
* [factor()](#factor)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory $productSearchApiFactory,
    \Psr\SimpleCache\CacheInterface $cache,
    bool $debug = false
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productSearchApiFactory`|`\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApiFactory`||
`$cache`|`\Psr\SimpleCache\CacheInterface`||
`$debug`|`bool`|`false`|

Return Value: `mixed`

### factor()

```php
public function factor(
    \Frontastic\Common\ReplicatorBundle\Domain\Project $project
): \Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||

Return Value: `\Frontastic\Common\ProductSearchApiBundle\Domain\ProductSearchApi`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
