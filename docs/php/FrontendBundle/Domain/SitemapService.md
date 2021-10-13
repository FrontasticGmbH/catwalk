#  SitemapService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\SitemapService`](../../../../src/php/FrontendBundle/Domain/SitemapService.php)

## Methods

* [__construct()](#__construct)
* [getExtensions()](#getextensions)
* [storeAll()](#storeall)
* [loadLatestByPath()](#loadlatestbypath)
* [cleanOutdated()](#cleanoutdated)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\SitemapGateway $sitemapGateway,
    mixed $sitemapExtensions
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$sitemapGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\SitemapGateway`||
`$sitemapExtensions`|`mixed`||

Return Value: `mixed`

### getExtensions()

```php
public function getExtensions(): iterable
```

Return Value: `iterable`

### storeAll()

```php
public function storeAll(
    array $sitemaps
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$sitemaps`|`array`||

Return Value: `mixed`

### loadLatestByPath()

```php
public function loadLatestByPath(
    string $path
): ?Sitemap
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$path`|`string`||

Return Value: ?[`Sitemap`](Sitemap.md)

### cleanOutdated()

```php
public function cleanOutdated(
    int $keep
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$keep`|`int`||

Return Value: `void`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
