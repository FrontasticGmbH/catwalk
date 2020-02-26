# `abstract`  TasticFieldHandlerV2

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\TasticFieldHandlerV2`](../../../../src/php/FrontendBundle/Domain/TasticFieldHandlerV2.php)

## Methods

* [getType()](#gettype)
* [handle()](#handle)

### getType()

```php
abstract public function getType(): string
```

Return Value: `string`

### handle()

```php
abstract public function handle(
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

