#  ProductApiFactoryDecorator

**Fully Qualified**: [`\Frontastic\Catwalk\ApiCoreBundle\Domain\ProductApiFactoryDecorator`](../../../../src/php/ApiCoreBundle/Domain/ProductApiFactoryDecorator.php)

**Implements**: `\Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory`

## Methods

* [__construct()](#__construct)
* [factor()](#factor)

### __construct()

```php
public function __construct(
    \Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory $productApiFactory,
    FacetService $facetService,
    \Psr\SimpleCache\CacheInterface $cache,
    bool $debug = false
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$productApiFactory`|`\Frontastic\Common\ProductApiBundle\Domain\ProductApiFactory`||
`$facetService`|[`FacetService`](../../FrontendBundle/Domain/FacetService.md)||
`$cache`|`\Psr\SimpleCache\CacheInterface`||
`$debug`|`bool`|`false`|

Return Value: `mixed`

### factor()

```php
public function factor(
    \Frontastic\Common\ReplicatorBundle\Domain\Project $project
): \Frontastic\Common\ProductApiBundle\Domain\ProductApi
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$project`|`\Frontastic\Common\ReplicatorBundle\Domain\Project`||

Return Value: `\Frontastic\Common\ProductApiBundle\Domain\ProductApi`

