#  BreadcrumbFieldHandler

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandler\BreadcrumbFieldHandler`](../../../../../src/php/FrontendBundle/Domain/TasticFieldHandler/BreadcrumbFieldHandler.php)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    NodeService $nodeService
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$nodeService`|[`NodeService`](../NodeService.md)||

Return Value: `mixed`

### getType()

```php
public function getType(): string
```

Return Value: `string`

### handle()

```php
public function handle(
    Context $context,
    Node $node,
    Page $page,
    mixed $fieldValue
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../../ApiCoreBundle/Domain/Context.md)||
`$node`|[`Node`](../Node.md)||
`$page`|[`Page`](../Page.md)||
`$fieldValue`|`mixed`||

Return Value: `mixed`

