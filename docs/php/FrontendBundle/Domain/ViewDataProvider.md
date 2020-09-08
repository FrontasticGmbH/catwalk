#  ViewDataProvider

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider`](../../../../src/php/FrontendBundle/Domain/ViewDataProvider.php)

## Methods

* [__construct()](#__construct)
* [fetchDataFor()](#fetchdatafor)

### __construct()

```php
public function __construct(
    StreamService $streamService,
    TasticFieldService $tasticFieldService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$streamService`|[`StreamService`](StreamService.md)||
`$tasticFieldService`|[`TasticFieldService`](TasticFieldService.md)||

Return Value: `mixed`

### fetchDataFor()

```php
public function fetchDataFor(
    Node $node,
    Context $context,
    array $streamParameters,
    Page $page = null
): ViewData
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$node`|[`Node`](Node.md)||
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$streamParameters`|`array`||
`$page`|[`Page`](Page.md)|`null`|

Return Value: [`ViewData`](ViewData.md)

