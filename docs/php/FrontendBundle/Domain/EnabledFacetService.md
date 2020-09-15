#  EnabledFacetService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\EnabledFacetService`](../../../../src/php/FrontendBundle/Domain/EnabledFacetService.php)

**Implements**: `\Frontastic\Common\ProductApiBundle\Domain\ProductApi\EnabledFacetService`

## Methods

* [__construct()](#__construct)
* [getEnabledFacetDefinitions()](#getenabledfacetdefinitions)

### __construct()

```php
public function __construct(
    FacetService $facetService,
    \Psr\SimpleCache\CacheInterface $cache
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$facetService`|[`FacetService`](FacetService.md)||
`$cache`|`\Psr\SimpleCache\CacheInterface`||

Return Value: `mixed`

### getEnabledFacetDefinitions()

```php
public function getEnabledFacetDefinitions(): array
```

Return Value: `array`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
