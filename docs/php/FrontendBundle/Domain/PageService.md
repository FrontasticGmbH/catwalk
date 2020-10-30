#  PageService

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\PageService`](../../../../src/php/FrontendBundle/Domain/PageService.php)

**Implements**: `\Frontastic\Common\ReplicatorBundle\Domain\Target`

## Methods

* [__construct()](#__construct)
* [lastUpdate()](#lastupdate)
* [replicate()](#replicate)
* [fill()](#fill)
* [fetchForNode()](#fetchfornode)
* [get()](#get)
* [store()](#store)
* [remove()](#remove)

### __construct()

```php
public function __construct(
    \Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway $pageGateway,
    \RulerZ\RulerZ $rulerz,
    \Frontastic\Catwalk\TrackingBundle\Domain\TrackingService $trackingService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$pageGateway`|`\Frontastic\Catwalk\FrontendBundle\Gateway\PageGateway`||
`$rulerz`|`\RulerZ\RulerZ`||
`$trackingService`|`\Frontastic\Catwalk\TrackingBundle\Domain\TrackingService`||

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
    Page $page,
    array $data
): Page
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$page`|[`Page`](Page.md)||
`$data`|`array`||

Return Value: [`Page`](Page.md)

### fetchForNode()

```php
public function fetchForNode(
    Node $node,
    Context $context
): Page
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||

Return Value: [`Page`](Page.md)

### get()

```php
public function get(
    string $pageId
): Page
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$pageId`|`string`||

Return Value: [`Page`](Page.md)

### store()

```php
public function store(
    Page $page
): Page
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$page`|[`Page`](Page.md)||

Return Value: [`Page`](Page.md)

### remove()

```php
public function remove(
    Page $page
): void
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$page`|[`Page`](Page.md)||

Return Value: `void`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
