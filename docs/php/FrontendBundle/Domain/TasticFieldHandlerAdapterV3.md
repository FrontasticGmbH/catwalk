#  TasticFieldHandlerAdapterV3

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandlerAdapterV3`](../../../../src/php/FrontendBundle/Domain/TasticFieldHandlerAdapterV3.php)

**Extends**: [`TasticFieldHandlerV3`](TasticFieldHandlerV3.md)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handle()](#handle)

### __construct()

```php
public function __construct(
    TasticFieldHandlerV2 $fieldHandler
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$fieldHandler`|[`TasticFieldHandlerV2`](TasticFieldHandlerV2.md)||

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
    Tastic $tastic,
    mixed $fieldValue
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$context`|[`Context`](../../ApiCoreBundle/Domain/Context.md)||
`$node`|[`Node`](Node.md)||
`$page`|[`Page`](Page.md)||
`$tastic`|[`Tastic`](Tastic.md)||
`$fieldValue`|`mixed`||

Return Value: `mixed`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
