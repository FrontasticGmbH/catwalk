#  TasticFieldHandlerAdapterV2

Fully Qualified: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandlerAdapterV2`](../../../../src/php/FrontendBundle/Domain/TasticFieldHandlerAdapterV2.php)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    TasticFieldHandler $fieldHandler
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$fieldHandler`|[`TasticFieldHandler`](TasticFieldHandler.md)||

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
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||
`$fieldValue`|`mixed`||

Return Value: `mixed`

