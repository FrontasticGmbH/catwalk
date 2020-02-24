#  FacetService

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\FacetService`](../../../../src/php/FrontendBundle/Domain/FacetService.php)

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [fill()](#fill)
* [get()](#get)
* [getEnabled()](#getenabled)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\FacetGateway $facetGateway
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$facetGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\FacetGateway`||

Return Value: `mixed`

### lastUpdate()

```php
public function lastUpdate(): string
```

Return Value: `string`

### replicate()

```php
public function replicate(
    array $updates
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$updates`|`array`||

Return Value: `void`

### fill()

```php
public function fill(
    Facet $facet,
    array $data
): Facet
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$facet`|[`Facet`](Facet.md)||
`$data`|`array`||

Return Value: [`Facet`](Facet.md)

### get()

```php
public function get(
    string $facetId
): Facet
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$facetId`|`string`||

Return Value: [`Facet`](Facet.md)

### getEnabled()

```php
public function getEnabled(): array
```

Return Value: `array`

